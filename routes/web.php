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
