<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:2',
            'parent_id' => 'nullable|exists:comments,id',
            'image' => 'nullable|image|max:5120', // 5MB max
        ]);

        $depth = 0;
        if ($validated['parent_id'] ?? null) {
            $parent = Comment::findOrFail($validated['parent_id']);
            $depth = min($parent->depth + 1, 3); // Max 3 levels deep
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('comments', config('filesystems.default'));
        }

        $post->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['body'],
            'image_path' => $imagePath,
            'depth' => $depth,
        ]);

        $post->update(['last_activity_at' => now()]);

        return back()->with('success', 'Reply posted!');
    }

    public function markSolution(Comment $comment)
    {
        $post = $comment->post;
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        // Remove any existing solution
        $post->comments()->where('is_solution', true)->update(['is_solution' => false]);

        $comment->update(['is_solution' => true]);
        $post->update(['is_resolved' => true]);

        return back()->with('success', 'Marked as solution!');
    }

    public function destroy(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id && !auth()->user()->isModerator()) {
            abort(403);
        }

        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
