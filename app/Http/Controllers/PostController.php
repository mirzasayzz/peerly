<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Forum;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function create()
    {
        $forums = Forum::with('category')->get()->groupBy('category.name');
        $tags = Tag::orderBy('name')->get();
        return view('posts.create', compact('forums', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',
            'forum_id' => 'required|exists:forums,id',
            'tags' => 'array|max:5',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,pdf,doc,docx|max:5120', // 5MB max
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', config('filesystems.default'));
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'forum_id' => $validated['forum_id'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(6),
            'body' => $validated['body'],
            'image_path' => $imagePath,
            'last_activity_at' => now(),
        ]);

        if (!empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'forum.category', 'tags', 'rootComments' => function ($q) {
            $q->with(['user', 'replies' => function ($q2) {
                $q2->with(['user', 'replies.user'])->orderBy('created_at');
            }])->orderBy('created_at');
        }]);
        $post->loadCount('comments');
        $post->loadSum('votes', 'value');

        $post->increment('view_count');

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        Gate::authorize('update', $post);
        $forums = Forum::with('category')->get()->groupBy('category.name');
        $tags = Tag::orderBy('name')->get();
        return view('posts.edit', compact('post', 'forums', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',
            'forum_id' => 'required|exists:forums,id',
            'tags' => 'array|max:5',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,pdf,doc,docx|max:5120', // 5MB max
        ]);

        $imagePath = $post->image_path;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image_path) {
                Storage::disk(config('filesystems.default'))->delete($post->image_path);
            }
            // Store new image
            $imagePath = $request->file('image')->store('posts', config('filesystems.default'));
        } elseif ($request->boolean('remove_image')) {
            // Delete old image if user wants to remove it
            if ($post->image_path) {
                Storage::disk(config('filesystems.default'))->delete($post->image_path);
            }
            $imagePath = null;
        }

        $post->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'forum_id' => $validated['forum_id'],
            'image_path' => $imagePath,
        ]);

        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Post updated!');
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);
        $forumSlug = $post->forum->slug;

        // Delete post image from S3 if exists
        if ($post->image_path) {
            Storage::disk(config('filesystems.default'))->delete($post->image_path);
        }

        $post->delete();

        return redirect()->route('forums.show', $forumSlug)
            ->with('success', 'Post deleted.');
    }
}
