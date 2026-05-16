<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Forum;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'image' => 'nullable|image|max:5120', // 5MB max
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

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['user', 'forum.category', 'tags', 'rootComments' => function ($q) {
                $q->with(['user', 'replies' => function ($q2) {
                    $q2->with(['user', 'replies.user'])->orderBy('created_at');
                }])->orderBy('created_at');
            }])
            ->withCount('comments')
            ->withSum('votes', 'value')
            ->firstOrFail();

        $post->increment('view_count');

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $forums = Forum::with('category')->get()->groupBy('category.name');
        $tags = Tag::orderBy('name')->get();
        return view('posts.edit', compact('post', 'forums', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',
            'forum_id' => 'required|exists:forums,id',
            'tags' => 'array|max:5',
            'tags.*' => 'exists:tags,id',
        ]);

        $post->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'forum_id' => $validated['forum_id'],
        ]);

        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Post updated!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $forumSlug = $post->forum->slug;
        $post->delete();

        return redirect()->route('forums.show', $forumSlug)
            ->with('success', 'Post deleted.');
    }
}
