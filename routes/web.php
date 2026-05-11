<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('garage');
});

Route::get('/maintenance', function () {
    return view('maintenance');
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
