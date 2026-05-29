<?php

namespace App\Http\Controllers;

use App\Models\CarProfile;
use App\Models\User;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function profile(string $slug): View
    {
        $user = User::query()
            ->where('profile_slug', $slug)
            ->with([
                'carProfiles' => fn ($query) => $query
                    ->where('visibility', 'public')
                    ->with(['photos' => fn ($photos) => $photos->where('visibility', 'public')->limit(3)])
                    ->withCount([
                        'photos' => fn ($photos) => $photos->where('visibility', 'public'),
                        'mods',
                        'communityPosts' => fn ($posts) => $posts->where('visibility', 'public'),
                    ]),
                'communityPosts' => fn ($query) => $query
                    ->where('visibility', 'public')
                    ->with([
                        'user',
                        'carProfile',
                        'comments' => fn ($comments) => $comments->with('user')->latest()->limit(2),
                    ])
                    ->withCount(['likes', 'comments'])
                    ->when(auth()->check(), fn ($posts) => $posts->withExists([
                        'likes as liked_by_user' => fn ($likes) => $likes->where('user_id', auth()->id()),
                    ]))
                    ->latest()
                    ->limit(6),
            ])
            ->firstOrFail();

        return view('public.profile', compact('user'));
    }

    public function garage(string $userSlug, string $carSlug): View
    {
        $user = User::where('profile_slug', $userSlug)->firstOrFail();
        $car = CarProfile::query()
            ->where('user_id', $user->id)
            ->where('slug', $carSlug)
            ->whereIn('visibility', ['public', 'unlisted'])
            ->with([
                'photos' => fn ($query) => $query->where('visibility', 'public')->limit(9),
                'mods' => fn ($query) => $query->limit(6),
                'buildTimelineEntries' => fn ($query) => $query->limit(5),
                'communityPosts' => fn ($query) => $query
                    ->where('visibility', 'public')
                    ->with([
                        'user',
                        'carProfile',
                        'comments' => fn ($comments) => $comments->with('user')->latest()->limit(2),
                    ])
                    ->withCount(['likes', 'comments'])
                    ->when(auth()->check(), fn ($posts) => $posts->withExists([
                        'likes as liked_by_user' => fn ($likes) => $likes->where('user_id', auth()->id()),
                    ]))
                    ->latest()
                    ->limit(5),
            ])
            ->firstOrFail();

        return view('public.garage', compact('user', 'car'));
    }
}
