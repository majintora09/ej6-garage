<?php

namespace App\Http\Controllers;

use App\Models\CarPhoto;
use Illuminate\Http\RedirectResponse;
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

    public function destroy(CarPhoto $carPhoto): RedirectResponse
    {
        abort_unless($carPhoto->carProfile->user_id === auth()->id(), 403);

        Storage::disk('public')->delete($carPhoto->path);
        $carPhoto->delete();

        return back()->with('status', __('ui.setup.photo_removed'));
    }
}
