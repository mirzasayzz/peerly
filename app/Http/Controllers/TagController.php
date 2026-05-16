<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')->orderByDesc('posts_count')->get();
        return view('tags.index', compact('tags'));
    }
}
