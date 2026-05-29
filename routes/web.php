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
use App\Http\Controllers\CarProfileController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PublicProfileController;
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

Route::get('/u/{slug}', [PublicProfileController::class, 'profile'])->name('public.profile');
Route::get('/u/{userSlug}/garage/{carSlug}', [PublicProfileController::class, 'garage'])->name('public.garage');
Route::get('/media/{path}', [MediaController::class, 'show'])->where('path', '.*')->name('media.show');
Route::get('/community/posts/{post}', [CommunityController::class, 'show'])->name('community.show');

Route::middleware('auth')->group(function () {
    Route::get('/garage/setup', [GarageSetupController::class, 'create'])->name('garage.setup');
    Route::post('/garage/setup', [GarageSetupController::class, 'store'])->name('garage.setup.store');
    Route::get('/cars', [CarProfileController::class, 'index'])->name('cars.index');
    Route::post('/cars', [CarProfileController::class, 'store'])->name('cars.store');
    Route::put('/cars/{car}', [CarProfileController::class, 'update'])->name('cars.update');
    Route::post('/cars/{car}/select', [CarProfileController::class, 'select'])->name('cars.select');
    Route::delete('/cars/{car}', [CarProfileController::class, 'destroy'])->name('cars.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'garage.profile'])->group(function () {
    Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
    Route::post('/community', [CommunityController::class, 'store'])->name('community.store');
    Route::delete('/community/{post}', [CommunityController::class, 'destroy'])->name('community.destroy');
    Route::post('/community/{post}/like', [CommunityController::class, 'toggleLike'])->name('community.like');
    Route::post('/community/{post}/comments', [CommunityController::class, 'storeComment'])->name('community.comments.store');

    Route::get('/garage/details', [GarageSetupController::class, 'edit'])->name('garage.profile.edit');
    Route::put('/garage/details', [GarageSetupController::class, 'update'])->name('garage.profile.update');
    Route::get('/car-photos/{carPhoto}', [CarPhotoController::class, 'show'])->name('car-photos.show');
    Route::post('/car-photos', [CarPhotoController::class, 'store'])->name('car-photos.store');
    Route::delete('/car-photos/{carPhoto}', [CarPhotoController::class, 'destroy'])->name('car-photos.destroy');

    Route::get('/maintenance', [MaintenanceController::class, 'index']);
    Route::post('/maintenance', [MaintenanceController::class, 'store']);
    Route::delete('/maintenance/{maintenance}', [MaintenanceController::class, 'destroy']);

    Route::get('/mods', [ModController::class, 'index']);
    Route::post('/mods', [ModController::class, 'store']);
    Route::delete('/mods/{mod}', [ModController::class, 'destroy']);

    Route::get('/inspection', [InspectionController::class, 'index']);
    Route::post('/inspection-points', [InspectionController::class, 'store']);
    Route::put('/inspection-points/{inspectionPoint}', [InspectionController::class, 'update'])->name('inspection-points.update');
    Route::post('/inspection-points/{inspectionPoint}/reset-position', [InspectionController::class, 'resetPosition'])->name('inspection-points.reset-position');
    Route::delete('/inspection-points/{inspectionPoint}', [InspectionController::class, 'destroy'])->name('inspection-points.destroy');

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
        $carProfile = auth()->user()->activeCar();
        $mods = collect();
        $dbError = null;

        try {
            $mods = Mod::where('user_id', auth()->id())
                ->where('car_profile_id', $carProfile->id)
                ->latest()
                ->get();
        } catch (\Throwable $e) {
            $dbError = $e->getMessage();
        }

        return view('calculator', compact('mods', 'dbError'));
    });
});

require __DIR__.'/auth.php';
