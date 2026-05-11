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
        'env_DB_CONNECTION' => env('DB_CONNECTION'),
        'env_DB_HOST' => env('DB_HOST'),
        'env_DB_PORT' => env('DB_PORT'),
        'env_DB_DATABASE' => env('DB_DATABASE'),
        'env_DB_USERNAME' => env('DB_USERNAME'),
        'env_MYSQLHOST' => env('MYSQLHOST'),
        'env_MYSQLPORT' => env('MYSQLPORT'),
        'env_MYSQLDATABASE' => env('MYSQLDATABASE'),
        'env_MYSQLUSER' => env('MYSQLUSER'),
    ]);
});
