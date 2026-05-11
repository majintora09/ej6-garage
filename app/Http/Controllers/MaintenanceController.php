<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Config;
use Throwable;

class MaintenanceController extends Controller
{
    private function forceMySqlConnection(): void
    {
        Config::set('database.default', 'mysql');

        Config::set('database.connections.mysql.host', getenv('DB_HOST') ?: getenv('MYSQLHOST'));
        Config::set('database.connections.mysql.port', getenv('DB_PORT') ?: getenv('MYSQLPORT'));
        Config::set('database.connections.mysql.database', getenv('DB_DATABASE') ?: getenv('MYSQLDATABASE'));
        Config::set('database.connections.mysql.username', getenv('DB_USERNAME') ?: getenv('MYSQLUSER'));
        Config::set('database.connections.mysql.password', getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD'));
    }

    public function index()
    {
        $this->forceMySqlConnection();

        $maintenances = collect();
        $dbError = null;

        try {
            $maintenances = Maintenance::latest()->get();
        } catch (Throwable $e) {
            $dbError = 'Current DB connection forced to MySQL. Error: ' . $e->getMessage();
        }

        return view('maintenance', compact('maintenances', 'dbError'));
    }

    public function store(Request $request)
    {
        $this->forceMySqlConnection();

        try {
            Maintenance::create([
                'title' => $request->title,
                'mileage' => $request->mileage,
                'cost' => $request->cost,
                'notes' => $request->notes,
                'service_date' => $request->service_date,
            ]);
        } catch (Throwable $e) {
            return redirect('/maintenance')->with('error', 'Current DB connection forced to MySQL. Error: ' . $e->getMessage());
        }

        return redirect('/maintenance');
    }

    public function destroy(Maintenance $maintenance)
    {
        $this->forceMySqlConnection();

        try {
            $maintenance->delete();
        } catch (Throwable $e) {
            return redirect('/maintenance')->with('error', 'Current DB connection forced to MySQL. Error: ' . $e->getMessage());
        }

        return redirect('/maintenance');
    }
}
