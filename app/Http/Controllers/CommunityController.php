<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function show(Request $request, CommunityPost $post): View
    {
        abort_unless($post->isVisibleTo($request->user()), 404);

        $post->load(['user', 'carProfile', 'comments.user'])
            ->loadCount(['likes', 'comments']);

        if ($request->user()) {
            $post->loadExists([
                'likes as liked_by_user' => fn ($query) => $query->where('user_id', $request->user()->id),
            ]);
        }

        return view('community.show', compact('post'));
    }

    public function index(Request $request): View
    {
        $posts = CommunityPost::query()
            ->with([
                'user',
                'carProfile',
                'comments' => fn ($query) => $query->with('user')->latest()->limit(3),
            ])
            ->withCount(['likes', 'comments'])
            ->withExists([
                'likes as liked_by_user' => fn ($query) => $query->where('user_id', $request->user()->id),
            ])
            ->where('visibility', 'public')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $activeCar = $request->user()->activeCar();

        return view('community.index', compact('posts', 'activeCar'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->user()->ensureProfileSlug();
        $activeCar = $request->user()->activeCar();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'body' => ['nullable', 'string', 'max:3000'],
            'category' => ['required', 'in:update,question,showcase,poll,trip,repair,mod'],
            'visibility' => ['required', 'in:public,unlisted,private'],
            'image' => ['nullable', 'image', 'max:4096'],
            'image_position' => ['nullable', 'in:center,top,bottom,left,right'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('community-posts', 'public');
        }

        CommunityPost::create([
            'user_id' => $request->user()->id,
            'car_profile_id' => $activeCar?->id,
            'title' => $validated['title'],
            'body' => $validated['body'] ?? null,
            'category' => $validated['category'],
            'visibility' => $validated['visibility'],
            'image_path' => $imagePath,
            'image_position' => $validated['image_position'] ?? 'center',
        ]);

        return back()->with('status', __('ui.community.posted'));
    }

    public function toggleLike(Request $request, CommunityPost $post): RedirectResponse
    {
        abort_unless($post->isVisibleTo($request->user()), 404);

        $like = $post->likes()->where('user_id', $request->user()->id)->first();

        if ($like) {
            $like->delete();
        } else {
            $post->likes()->create(['user_id' => $request->user()->id]);
        }

        return back();
    }

    public function storeComment(Request $request, CommunityPost $post): RedirectResponse
    {
        abort_unless($post->isVisibleTo($request->user()), 404);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1200'],
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return back()->with('status', __('ui.community.commented'));
    }

    public function destroy(Request $request, CommunityPost $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return back()->with('status', __('ui.community.deleted'));
    }
}
