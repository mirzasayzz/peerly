<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\Category;
use App\Models\Post;

class ForumController extends Controller
{
    public function index()
    {
        $categories = Category::with(['forums' => function ($q) {
            $q->withCount('posts')->with('latestPost.user');
        }])->orderBy('sort_order')->get();

        return view('forums.index', compact('categories'));
    }

    public function show(Forum $forum)
    {
        $posts = $forum->posts()
            ->with(['user', 'tags'])
            ->withCount('comments')
            ->withSum('votes', 'value')
            ->orderByDesc('is_pinned')
            ->orderByDesc('last_activity_at')
            ->paginate(20);

        return view('forums.show', compact('forum', 'posts'));
    }
}
