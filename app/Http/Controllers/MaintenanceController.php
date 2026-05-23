<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use Throwable;

class MaintenanceController extends Controller
{
    public function index()
    {
        $carProfile = auth()->user()->activeCar();
        $maintenances = collect();
        $dbError = null;

        try {
            $maintenances = Maintenance::where('user_id', auth()->id())
                ->where('car_profile_id', $carProfile->id)
                ->latest()
                ->get();
        } catch (Throwable $e) {
            $dbError = $e->getMessage();
        }

        return view('maintenance', compact('maintenances', 'dbError', 'carProfile'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'category' => ['nullable', 'string', 'max:255'],
                'mileage' => ['nullable', 'integer', 'min:0'],
                'cost' => ['nullable', 'numeric', 'min:0'],
                'notes' => ['nullable', 'string'],
                'service_date' => ['nullable', 'date'],
                'next_due_date' => ['nullable', 'date'],
                'next_due_mileage' => ['nullable', 'integer', 'min:0'],
            ]);

            Maintenance::create([
                ...$validated,
                'user_id' => auth()->id(),
                'car_profile_id' => auth()->user()->activeCar()->id,
            ]);
        } catch (Throwable $e) {
            return redirect('/maintenance')->with('error', $e->getMessage());
        }

        return redirect('/maintenance');
    }

    public function destroy(Maintenance $maintenance)
    {
        try {
            abort_unless($maintenance->user_id === auth()->id(), 403);
            abort_unless($maintenance->car_profile_id === auth()->user()->activeCar()->id, 403);
            $maintenance->delete();
        } catch (Throwable $e) {
            return redirect('/maintenance')->with('error', $e->getMessage());
        }

        return redirect('/maintenance');
    }
}
