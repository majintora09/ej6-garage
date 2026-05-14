<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('garage');
});

Route::get('/maintenance', function () {
    return view('maintenance-disabled');
});

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
        'app_is_running' => true,
        'message' => 'Database temporarily disabled',
    ]);
});
