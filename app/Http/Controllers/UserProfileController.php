<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(string $username)
    {
        $query = User::where('username', $username);
        
        if (is_numeric($username)) {
            $query->orWhere('id', $username);
        }

        $user = $query->firstOrFail();

        $posts = $user->posts()
            ->with(['forum', 'tags'])
            ->withCount('comments')
            ->withSum('votes', 'value')
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'posts' => $user->posts()->count(),
            'comments' => $user->comments()->count(),
            'followers' => $user->followers()->count(),
            'following' => $user->following()->count(),
        ];

        return view('profiles.show', compact('user', 'posts', 'stats'));
    }

    public function follow(User $user)
    {
        if (auth()->id() === $user->id) abort(403);

        auth()->user()->following()->toggle($user->id);
        return back();
    }
}
