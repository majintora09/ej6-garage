<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mod;
use Throwable;

class ModController extends Controller
{
    public function index()
    {
        $carProfile = auth()->user()->activeCar();

        if (! $carProfile) {
            return redirect()->route('garage.setup');
        }

        $mods = collect();
        $dbError = null;

        try {
            $mods = Mod::where('user_id', auth()->id())
                ->where('car_profile_id', $carProfile->id)
                ->latest()
                ->get();
        } catch (Throwable $e) {
            $dbError = $e->getMessage();
        }

        return view('mods', compact('mods', 'dbError', 'carProfile'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'category' => ['nullable', 'string', 'max:255'],
                'price' => ['nullable', 'numeric', 'min:0'],
                'priority' => ['nullable', 'string', 'max:255'],
                'status' => ['nullable', 'string', 'max:255'],
                'link' => ['nullable', 'string', 'max:255'],
                'notes' => ['nullable', 'string'],
            ]);

            Mod::create([
                ...$validated,
                'user_id' => auth()->id(),
                'car_profile_id' => auth()->user()->activeCar()->id,
            ]);
        } catch (Throwable $e) {
            return redirect('/mods')->with('error', $e->getMessage());
        }

        return redirect('/mods');
    }

    public function destroy(Mod $mod)
    {
        try {
            abort_unless($mod->user_id === auth()->id(), 403);
            abort_unless($mod->car_profile_id === auth()->user()->activeCar()->id, 403);
            $mod->delete();
        } catch (Throwable $e) {
            return redirect('/mods')->with('error', $e->getMessage());
        }

        return redirect('/mods');
    }
}
