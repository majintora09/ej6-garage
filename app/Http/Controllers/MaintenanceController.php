<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::latest()->get();

        return view('maintenance', compact('maintenances'));
    }

    public function store(Request $request)
    {
        Maintenance::create([
            'title' => $request->title,
            'mileage' => $request->mileage,
            'cost' => $request->cost,
            'notes' => $request->notes,
            'service_date' => $request->service_date,
        ]);

        return redirect('/maintenance');
    }
}
