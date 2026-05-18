<?php

namespace App\Http\Controllers;

use App\Models\InspectionPoint;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function index()
    {
        $points = InspectionPoint::latest()->get();

        $maintenances = Maintenance::latest()
            ->get()
            ->groupBy('category');

        return view('inspection', compact('points', 'maintenances'));
    }

    public function store(Request $request)
    {
        $point = InspectionPoint::create([
            'name' => $request->name,
            'category' => $request->category,
            'status' => $request->status,
            'priority' => $request->priority,
            'description' => $request->description,
            'x' => $request->x,
            'y' => $request->y,
            'z' => $request->z,
        ]);

        return response()->json($point);
    }

    public function destroy(InspectionPoint $inspectionPoint)
    {
        $inspectionPoint->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
