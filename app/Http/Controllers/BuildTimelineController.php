<?php

namespace App\Http\Controllers;

use App\Models\BuildTimelineEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BuildTimelineController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $carProfile = $user->activeCar();

        $entries = BuildTimelineEntry::where('user_id', $user->id)
            ->where('car_profile_id', $carProfile->id)
            ->latest('event_date')
            ->latest()
            ->get();

        return view('timeline', compact('carProfile', 'entries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $carProfile = $user->activeCar();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:4000'],
            'event_date' => ['nullable', 'date'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'image' => ['nullable', 'image', 'max:8192'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('timeline/'.$carProfile->id, 'public');
        }

        unset($validated['image']);

        BuildTimelineEntry::create([
            ...$validated,
            'user_id' => $user->id,
            'car_profile_id' => $carProfile->id,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('timeline.index')->with('status', __('ui.timeline.created'));
    }

    public function destroy(BuildTimelineEntry $timelineEntry): RedirectResponse
    {
        abort_unless($timelineEntry->user_id === auth()->id(), 403);
        abort_unless($timelineEntry->car_profile_id === auth()->user()->activeCar()->id, 403);

        if ($timelineEntry->image_path) {
            Storage::disk('public')->delete($timelineEntry->image_path);
        }

        $timelineEntry->delete();

        return redirect()->route('timeline.index')->with('status', __('ui.timeline.deleted'));
    }
}
