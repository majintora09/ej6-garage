<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use Throwable;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = collect();
        $dbError = null;

        try {
            $maintenances = Maintenance::latest()->get();
        } catch (Throwable $e) {
            $dbError = $e->getMessage();
        }

        return view('maintenance', compact('maintenances', 'dbError'));
    }

    public function store(Request $request)
    {
        try {
            Maintenance::create([
                'title' => $request->title,
                'mileage' => $request->mileage,
                'cost' => $request->cost,
                'notes' => $request->notes,
                'service_date' => $request->service_date,
            ]);
        } catch (Throwable $e) {
            return redirect('/maintenance')->with('error', $e->getMessage());
        }

        return redirect('/maintenance');
    }

    public function destroy(Maintenance $maintenance)
    {
        try {
            $maintenance->delete();
        } catch (Throwable $e) {
            return redirect('/maintenance')->with('error', $e->getMessage());
        }

        return redirect('/maintenance');
    }
}
