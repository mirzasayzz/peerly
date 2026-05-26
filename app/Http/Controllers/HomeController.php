<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;

class HomeController extends Controller
{
    public function index()
    {
        $sort = request('sort', 'trending');

        $posts = Post::with(['user', 'forum.category', 'tags'])
            ->withCount('comments')
            ->withSum('votes', 'value');

        switch ($sort) {
            case 'new':
                $posts->orderByDesc('created_at');
                break;
            case 'top':
                $posts->orderByDesc('votes_sum_value');
                break;
            case 'unanswered':
                $posts->whereRaw('is_resolved = false')
                      ->has('comments', '=', 0)
                      ->orderByDesc('created_at');
                break;
            default: // trending
                $posts->orderByDesc('is_pinned')
                      ->orderByDesc('last_activity_at');
                break;
        }

        $posts = $posts->paginate(15);

        return view('home', compact('posts', 'sort'));
    }
}
