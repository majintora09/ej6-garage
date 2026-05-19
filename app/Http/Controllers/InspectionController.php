<?php

namespace App\Http\Controllers;

use App\Models\InspectionPoint;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function index()
    {
        $carProfile = auth()->user()->carProfile;

        if (! $carProfile) {
            return redirect()->route('garage.setup');
        }

        $points = InspectionPoint::where('car_profile_id', $carProfile->id)
            ->latest()
            ->get();

        $maintenances = Maintenance::latest()
            ->get()
            ->groupBy('category');

        return view('inspection', compact('points', 'maintenances', 'carProfile'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
            'priority' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'x' => ['required', 'numeric'],
            'y' => ['required', 'numeric'],
            'z' => ['required', 'numeric'],
            'normalized_x' => ['required', 'numeric', 'between:0,1'],
            'normalized_y' => ['required', 'numeric', 'between:0,1'],
            'normalized_z' => ['required', 'numeric', 'between:0,1'],
        ]);

        $point = InspectionPoint::create([
            ...$validated,
            'car_profile_id' => auth()->user()->carProfile->id,
        ]);

        return response()->json($point);
    }

    public function destroy(InspectionPoint $inspectionPoint)
    {
        abort_unless($inspectionPoint->car_profile_id === auth()->user()->carProfile->id, 403);

        $inspectionPoint->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
