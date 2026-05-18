<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mod;
use Throwable;

class ModController extends Controller
{
    public function index()
    {
        $mods = collect();
        $dbError = null;

        try {
            $mods = Mod::latest()->get();
        } catch (Throwable $e) {
            $dbError = $e->getMessage();
        }

        return view('mods', compact('mods', 'dbError'));
    }

    public function store(Request $request)
    {
        try {
            Mod::create([
                'name' => $request->name,
                'category' => $request->category,
                'price' => $request->price,
                'priority' => $request->priority,
                'status' => $request->status,
                'link' => $request->link,
                'notes' => $request->notes,
            ]);
        } catch (Throwable $e) {
            return redirect('/mods')->with('error', $e->getMessage());
        }

        return redirect('/mods');
    }

    public function destroy(Mod $mod)
    {
        try {
            $mod->delete();
        } catch (Throwable $e) {
            return redirect('/mods')->with('error', $e->getMessage());
        }

        return redirect('/mods');
    }
}
