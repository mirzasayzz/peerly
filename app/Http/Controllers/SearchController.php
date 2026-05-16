<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $tagSlug = $request->input('tag');

        $posts = Post::query()
            ->with(['user', 'forum', 'tags'])
            ->withCount('comments')
            ->withSum('votes', 'value');

        if ($query) {
            $posts->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('body', 'LIKE', "%{$query}%");
            });
        }

        if ($tagSlug) {
            $posts->whereHas('tags', fn ($q) => $q->where('slug', $tagSlug));
        }

        $posts = $posts->orderByDesc('last_activity_at')->paginate(15);

        return view('search.index', compact('posts', 'query', 'tagSlug'));
    }
}
