<?php

namespace App\Http\Controllers;

use App\Models\CarProfile;
use App\Services\DominantColorExtractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GarageSetupController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (auth()->user()->carProfile()->exists()) {
            return redirect()->route('garage.profile.edit');
        }

        return view('garage-setup', [
            'carProfile' => null,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'make' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'chassis' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'engine' => ['nullable', 'string', 'max:255'],
            'color_name' => ['nullable', 'string', 'max:255'],
            'color_code' => ['nullable', 'string', 'max:255'],
            'theme_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'interior' => ['nullable', 'string', 'max:255'],
            'body_type' => ['required', 'in:coupe,hatchback,sedan,wagon,suv,pickup,motorcycle,other'],
            'model_path' => ['nullable', 'string', 'max:255', 'regex:/^\/(models|storage)\/.+\.(glb|gltf|stl)$/i'],
            'build_vibe' => ['nullable', 'string', 'max:1000'],
            'known_issues' => ['nullable', 'string', 'max:4000'],
            'future_plans' => ['nullable', 'string', 'max:4000'],
            'restoration_progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'car_photos' => ['nullable', 'array', 'max:6'],
            'car_photos.*' => ['image', 'max:8192'],
        ]);

        unset($validated['car_photos']);

        $themeColor = $this->resolveThemeColor($validated['theme_color'] ?? null, $request);

        $carProfile = auth()->user()->carProfile()->create([
            ...$validated,
            'theme_color' => $themeColor,
        ]);

        $this->storeUploadedPhotos($request, $carProfile);

        return redirect()->route('dashboard');
    }

    public function edit(): View
    {
        return view('garage-setup', [
            'carProfile' => auth()->user()->carProfile()->with('photos')->first(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'make' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'chassis' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'engine' => ['nullable', 'string', 'max:255'],
            'color_name' => ['nullable', 'string', 'max:255'],
            'color_code' => ['nullable', 'string', 'max:255'],
            'theme_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'interior' => ['nullable', 'string', 'max:255'],
            'body_type' => ['required', 'in:coupe,hatchback,sedan,wagon,suv,pickup,motorcycle,other'],
            'model_path' => ['nullable', 'string', 'max:255', 'regex:/^\/(models|storage)\/.+\.(glb|gltf|stl)$/i'],
            'build_vibe' => ['nullable', 'string', 'max:1000'],
            'known_issues' => ['nullable', 'string', 'max:4000'],
            'future_plans' => ['nullable', 'string', 'max:4000'],
            'restoration_progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'car_photos' => ['nullable', 'array', 'max:6'],
            'car_photos.*' => ['image', 'max:8192'],
        ]);

        unset($validated['car_photos']);

        $carProfile = auth()->user()->carProfile;

        $themeColor = $this->resolveThemeColor($validated['theme_color'] ?? null, $request);

        $carProfile->update([
            ...$validated,
            'theme_color' => $themeColor,
        ]);

        $this->storeUploadedPhotos($request, $carProfile);

        return redirect()->route('dashboard')->with('status', __('ui.setup.updated'));
    }

    private function storeUploadedPhotos(Request $request, CarProfile $carProfile): void
    {
        foreach ($request->file('car_photos', []) as $photo) {
            $path = $photo->store('car-photos/'.$carProfile->id, 'public');

            $carProfile->photos()->create([
                'path' => $path,
                'original_name' => $photo->getClientOriginalName(),
            ]);
        }
    }

    private function extractThemeColor(Request $request): ?string
    {
        $photo = collect($request->file('car_photos', []))->first();

        return $photo ? app(DominantColorExtractor::class)->fromUploadedFile($photo) : null;
    }

    private function resolveThemeColor(?string $selectedColor, Request $request): string
    {
        if (! $selectedColor || strtolower($selectedColor) === '#76ff9f') {
            return $this->extractThemeColor($request) ?: ($selectedColor ?: '#76ff9f');
        }

        return $selectedColor;
    }
}
