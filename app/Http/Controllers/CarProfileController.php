<?php

namespace App\Http\Controllers;

use App\Models\CarProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CarProfileController extends Controller
{
    public function index(): View
    {
        return view('cars.index', [
            'cars' => auth()->user()->carProfiles()->withCount(['photos', 'mods', 'maintenances', 'inspectionPoints'])->get(),
            'activeCar' => auth()->user()->activeCar(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedCar($request);

        $car = auth()->user()->carProfiles()->create([
            ...$validated,
            'theme_color' => $this->normalizeTheme($validated['theme_color'] ?? null),
            'secondary_theme_color' => $validated['secondary_theme_color'] ?? null,
            'slug' => $this->uniqueSlug($validated['year'] ?? null, $validated['make'], $validated['model']),
            'visibility' => $validated['visibility'] ?? 'private',
        ]);

        auth()->user()->forceFill(['active_car_profile_id' => $car->id])->save();

        return redirect()->route('cars.index')->with('status', __('ui.cars.created'));
    }

    public function update(Request $request, CarProfile $car): RedirectResponse
    {
        $this->authorizeCar($car);

        $validated = $this->validatedCar($request);

        $car->update([
            ...$validated,
            'theme_color' => $this->normalizeTheme($validated['theme_color'] ?? null),
            'secondary_theme_color' => $validated['secondary_theme_color'] ?? null,
            'slug' => ($validated['slug'] ?? null)
                ? $this->uniqueSlug(null, $validated['slug'], '', $car->id, true)
                : ($car->slug ?: $this->uniqueSlug($validated['year'] ?? null, $validated['make'], $validated['model'], $car->id)),
            'visibility' => $validated['visibility'] ?? 'private',
        ]);

        return redirect()->route('cars.index')->with('status', __('ui.cars.updated'));
    }

    public function select(CarProfile $car): RedirectResponse
    {
        $this->authorizeCar($car);

        auth()->user()->forceFill(['active_car_profile_id' => $car->id])->save();

        return redirect()->route('dashboard')->with('status', __('ui.cars.selected', ['car' => trim($car->make.' '.$car->model)]));
    }

    public function destroy(CarProfile $car): RedirectResponse
    {
        $this->authorizeCar($car);

        $wasActive = auth()->user()->active_car_profile_id === $car->id;
        $car->delete();

        if ($wasActive) {
            $nextCar = auth()->user()->carProfiles()->oldest()->first();
            auth()->user()->forceFill(['active_car_profile_id' => $nextCar?->id])->save();
        }

        if (! auth()->user()->carProfiles()->exists()) {
            return redirect()->route('garage.setup')->with('status', __('ui.cars.deleted'));
        }

        return redirect()->route('cars.index')->with('status', __('ui.cars.deleted'));
    }

    private function validatedCar(Request $request): array
    {
        return $request->validate([
            'make' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'chassis' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'engine' => ['nullable', 'string', 'max:255'],
            'body_type' => ['required', 'in:coupe,hatchback,sedan,wagon,suv,pickup,motorcycle,other'],
            'color_name' => ['nullable', 'string', 'max:255'],
            'color_code' => ['nullable', 'string', 'max:255'],
            'theme_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_theme_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'interior' => ['nullable', 'string', 'max:255'],
            'build_vibe' => ['nullable', 'string', 'max:1000'],
            'visibility' => ['nullable', 'in:private,unlisted,public'],
            'slug' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function authorizeCar(CarProfile $car): void
    {
        abort_unless($car->user_id === auth()->id(), 403);
    }

    private function normalizeTheme(?string $themeColor): string
    {
        return $themeColor && strtolower($themeColor) !== '#76ff9f' ? $themeColor : '#8b5cf6';
    }

    private function uniqueSlug(?int $year, string $make, string $model, ?int $ignoreId = null, bool $rawSlug = false): string
    {
        $base = $rawSlug ? Str::slug($make) : Str::slug(trim(($year ? $year.' ' : '').$make.' '.$model));
        $base = $base ?: 'garage-car';
        $slug = $base;
        $suffix = 2;

        while (CarProfile::where('user_id', auth()->id())
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
