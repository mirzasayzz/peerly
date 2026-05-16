<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{

    public function toggle(Request $request)
    {
        $validated = $request->validate(['post_id' => 'required|exists:posts,id']);

        $existing = Bookmark::where('user_id', auth()->id())
            ->where('post_id', $validated['post_id'])
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['bookmarked' => false]);
        }

        Bookmark::create([
            'user_id' => auth()->id(),
            'post_id' => $validated['post_id'],
        ]);

        return response()->json(['bookmarked' => true]);
    }

    public function index()
    {
        $bookmarks = auth()->user()->bookmarks()
            ->with(['post' => function ($q) {
                $q->with(['user', 'forum', 'tags'])->withCount('comments')->withSum('votes', 'value');
            }])
            ->latest()
            ->paginate(15);

        return view('bookmarks.index', compact('bookmarks'));
    }
}
