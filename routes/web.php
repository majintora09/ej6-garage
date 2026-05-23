<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ModController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\GarageSetupController;
use App\Http\Controllers\CarPhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BuildTimelineController;
use App\Models\Mod;

Route::get('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'de', 'fr', 'pt', 'lb'], true), 404);

    session(['locale' => $locale]);

    return back();
})->name('language.switch');

Route::get('/', DashboardController::class)->middleware('auth');

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/garage/setup', [GarageSetupController::class, 'create'])->name('garage.setup');
    Route::post('/garage/setup', [GarageSetupController::class, 'store'])->name('garage.setup.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'garage.profile'])->group(function () {
    Route::get('/garage/details', [GarageSetupController::class, 'edit'])->name('garage.profile.edit');
    Route::put('/garage/details', [GarageSetupController::class, 'update'])->name('garage.profile.update');
    Route::get('/car-photos/{carPhoto}', [CarPhotoController::class, 'show'])->name('car-photos.show');
    Route::delete('/car-photos/{carPhoto}', [CarPhotoController::class, 'destroy'])->name('car-photos.destroy');

    Route::get('/maintenance', [MaintenanceController::class, 'index']);
    Route::post('/maintenance', [MaintenanceController::class, 'store']);
    Route::delete('/maintenance/{maintenance}', [MaintenanceController::class, 'destroy']);

    Route::get('/mods', [ModController::class, 'index']);
    Route::post('/mods', [ModController::class, 'store']);
    Route::delete('/mods/{mod}', [ModController::class, 'destroy']);

    Route::get('/inspection', [InspectionController::class, 'index']);
    Route::post('/inspection-points', [InspectionController::class, 'store']);
    Route::delete('/inspection-points/{inspectionPoint}', [InspectionController::class, 'destroy']);

    Route::get('/timeline', [BuildTimelineController::class, 'index'])->name('timeline.index');
    Route::post('/timeline', [BuildTimelineController::class, 'store'])->name('timeline.store');
    Route::delete('/timeline/{timelineEntry}', [BuildTimelineController::class, 'destroy'])->name('timeline.destroy');

    Route::get('/parts', function () {
        return view('parts');
    });

    Route::get('/gallery', function () {
        return view('gallery');
    });

    Route::get('/calculator', function () {
        $mods = collect();
        $dbError = null;

        try {
            $mods = Mod::latest()->get();
        } catch (\Throwable $e) {
            $dbError = $e->getMessage();
        }

        return view('calculator', compact('mods', 'dbError'));
    });
});

require __DIR__.'/auth.php';
