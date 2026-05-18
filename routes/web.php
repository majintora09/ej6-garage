<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ModController;

Route::get('/', function () {
    return view('garage');
});

Route::get('/maintenance', [MaintenanceController::class, 'index']);
Route::post('/maintenance', [MaintenanceController::class, 'store']);
Route::delete('/maintenance/{maintenance}', [MaintenanceController::class, 'destroy']);

Route::get('/mods', function () {
    Route::get('/mods', [ModController::class, 'index']);
    Route::post('/mods', [ModController::class, 'store']);
    Route::delete('/mods/{mod}', [ModController::class, 'destroy']);
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
