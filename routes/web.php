<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaintenanceController;

Route::get('/', function () {
    return view('garage');
});

Route::get('/maintenance', [MaintenanceController::class, 'index']);
Route::post('/maintenance', [MaintenanceController::class, 'store']);
Route::delete('/maintenance/{maintenance}', [MaintenanceController::class, 'destroy']);

Route::get('/mods', function () {
    return view('mods');
});

Route::get('/parts', function () {
    return view('parts');
});

Route::get('/gallery', function () {
    return view('gallery');
});

Route::get('/calculator', function () {
    return view('calculator');
});

Route::get('/debug-db', function () {
    return response()->json([
        'config_default' => config('database.default'),

        'config_host' => config('database.connections.mysql.host'),
        'config_port' => config('database.connections.mysql.port'),
        'config_database' => config('database.connections.mysql.database'),
        'config_username' => config('database.connections.mysql.username'),

        'getenv_DB_CONNECTION' => getenv('DB_CONNECTION'),
        'getenv_DB_HOST' => getenv('DB_HOST'),
        'getenv_DB_PORT' => getenv('DB_PORT'),
        'getenv_DB_DATABASE' => getenv('DB_DATABASE'),
        'getenv_DB_USERNAME' => getenv('DB_USERNAME'),

        'server_DB_CONNECTION' => $_SERVER['DB_CONNECTION'] ?? null,
        'server_DB_HOST' => $_SERVER['DB_HOST'] ?? null,
        'server_DB_PORT' => $_SERVER['DB_PORT'] ?? null,
        'server_DB_DATABASE' => $_SERVER['DB_DATABASE'] ?? null,
        'server_DB_USERNAME' => $_SERVER['DB_USERNAME'] ?? null,
    ]);
});
