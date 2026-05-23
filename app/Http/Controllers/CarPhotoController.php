<?php

namespace App\Http\Controllers;

use App\Models\CarPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CarPhotoController extends Controller
{
    public function show(CarPhoto $carPhoto): StreamedResponse
    {
        abort_unless($carPhoto->carProfile->user_id === auth()->id(), 403);
        abort_unless(Storage::disk('public')->exists($carPhoto->path), 404);

        return Storage::disk('public')->response($carPhoto->path);
    }

    public function store(Request $request): RedirectResponse
    {
        $carProfile = auth()->user()->activeCar();

        $validated = $request->validate([
            'photos' => ['required', 'array', 'max:8'],
            'photos.*' => ['image', 'max:8192'],
            'category' => ['required', 'in:exterior,interior,rust,maintenance,mods,receipts,inspiration'],
            'caption' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'visibility' => ['nullable', 'in:private,unlisted,public'],
        ]);

        foreach ($request->file('photos', []) as $photo) {
            $path = $photo->store('car-photos/'.$carProfile->id, 'public');

            $carProfile->photos()->create([
                'path' => $path,
                'original_name' => $photo->getClientOriginalName(),
                'category' => $validated['category'],
                'caption' => $validated['caption'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'visibility' => $validated['visibility'] ?? 'private',
            ]);
        }

        return back()->with('status', __('ui.gallery.uploaded'));
    }

    public function destroy(CarPhoto $carPhoto): RedirectResponse
    {
        abort_unless($carPhoto->carProfile->user_id === auth()->id(), 403);

        Storage::disk('public')->delete($carPhoto->path);
        $carPhoto->delete();

        return back()->with('status', __('ui.setup.photo_removed'));
    }
}
