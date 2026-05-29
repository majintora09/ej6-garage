<?php

namespace App\Http\Controllers;

use App\Models\InspectionPoint;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function index()
    {
        $carProfile = auth()->user()->activeCar();

        if (! $carProfile) {
            return redirect()->route('garage.setup');
        }

        $points = InspectionPoint::where('car_profile_id', $carProfile->id)
            ->latest()
            ->get();

        $maintenances = Maintenance::where('user_id', auth()->id())
            ->where('car_profile_id', $carProfile->id)
            ->latest()
            ->get()
            ->groupBy('category');

        return view('inspection', compact('points', 'maintenances', 'carProfile'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePoint($request);

        $point = InspectionPoint::create([
            ...$this->normalizePointPosition($validated),
            'car_profile_id' => auth()->user()->activeCar()->id,
        ]);

        return response()->json($point);
    }

    public function update(Request $request, InspectionPoint $inspectionPoint)
    {
        $this->authorizePoint($inspectionPoint);

        $validated = $this->validatePoint($request, false);
        $inspectionPoint->update($this->normalizePointPosition($validated, $inspectionPoint));

        if ($request->expectsJson()) {
            return response()->json($inspectionPoint->fresh());
        }

        return back()->with('status', __('ui.inspection.point_updated'));
    }

    public function resetPosition(Request $request, InspectionPoint $inspectionPoint)
    {
        $this->authorizePoint($inspectionPoint);

        $inspectionPoint->update([
            'x' => 0,
            'y' => 1,
            'z' => 1,
            'normalized_x' => 0.5,
            'normalized_y' => 0.62,
            'normalized_z' => 0.88,
        ]);

        if ($request->expectsJson()) {
            return response()->json($inspectionPoint->fresh());
        }

        return redirect('/inspection?point='.$inspectionPoint->id)->with('status', __('ui.inspection.point_reset'));
    }

    public function destroy(Request $request, InspectionPoint $inspectionPoint)
    {
        $this->authorizePoint($inspectionPoint);

        $inspectionPoint->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
            ]);
        }

        return back()->with('status', __('ui.inspection.point_deleted'));
    }

    private function validatePoint(Request $request, bool $creating = true): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
            'priority' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'x' => [$creating ? 'required' : 'nullable', 'numeric'],
            'y' => [$creating ? 'required' : 'nullable', 'numeric'],
            'z' => [$creating ? 'required' : 'nullable', 'numeric'],
            'normalized_x' => [$creating ? 'required' : 'nullable', 'numeric', 'between:0,1'],
            'normalized_y' => [$creating ? 'required' : 'nullable', 'numeric', 'between:0,1'],
            'normalized_z' => [$creating ? 'required' : 'nullable', 'numeric', 'between:0,1'],
        ]);
    }

    private function normalizePointPosition(array $validated, ?InspectionPoint $existingPoint = null): array
    {
        $defaults = [
            'x' => $existingPoint?->x ?? 0,
            'y' => $existingPoint?->y ?? 1,
            'z' => $existingPoint?->z ?? 1,
            'normalized_x' => $existingPoint?->normalized_x ?? 0.5,
            'normalized_y' => $existingPoint?->normalized_y ?? 0.62,
            'normalized_z' => $existingPoint?->normalized_z ?? 0.88,
        ];

        foreach ($defaults as $key => $value) {
            if (! isset($validated[$key]) || ! is_numeric($validated[$key])) {
                $validated[$key] = $value;
            }
        }

        return $validated;
    }

    private function authorizePoint(InspectionPoint $inspectionPoint): void
    {
        abort_unless($inspectionPoint->car_profile_id === auth()->user()->activeCar()->id, 403);
    }
}
