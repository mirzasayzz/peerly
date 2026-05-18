<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminInvitation;
use App\Models\Post;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/');
        }
        
        $admins = User::where('role', 'admin')->get();
        $pendingInvites = AdminInvitation::where('status', 'pending')
            ->where('expires_at', '>', now())
            ->get();
        return view('admin.dashboard', compact('admins', 'pendingInvites'));
    }

    public function users()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $users = User::with('customBadges')->orderBy('created_at', 'desc')->paginate(15);
        // Collect emails that have a pending (not yet completed) invite
        $pendingEmails = AdminInvitation::where('status', 'pending')
            ->where('expires_at', '>', now())
            ->pluck('email');
        return view('admin.users', compact('users', 'pendingEmails'));
    }

    public function posts()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $posts = Post::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.posts', compact('posts'));
    }

    public function comments()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $comments = \App\Models\Comment::with(['user', 'post'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.comments', compact('comments'));
    }

    public function deleteUser(User $user)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        if ($user->email === 'tubamirza822@gmail.com') abort(403, 'Cannot delete super admin.');
        
        $user->delete();
        return back()->with('status', 'User deleted successfully.');
    }

    public function deletePost(Post $post)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $post->delete();
        return back()->with('status', 'Post deleted successfully.');
    }

    public function deleteComment(\App\Models\Comment $comment)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $comment->delete();
        return back()->with('status', 'Reply deleted successfully.');
    }

    public function togglePinPost(Post $post)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $newStatus = !$post->is_pinned;
        
        // Use DB update to explicitly cast for PostgreSQL strict boolean fields
        \Illuminate\Support\Facades\DB::table('posts')
            ->where('id', $post->id)
            ->update(['is_pinned' => $newStatus ? 'true' : 'false']);
        
        $status = $newStatus ? 'pinned' : 'unpinned';
        return back()->with('status', "Post {$status} successfully.");
    }

    public function addBadge(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'icon' => 'required|string|max:10',
            'color' => 'required|string|regex:/^#[0-9a-f]{6}$/i',
        ]);

        UserBadge::create([
            'user_id' => $user->id,
            'label' => $validated['label'],
            'icon' => $validated['icon'],
            'color' => $validated['color'],
        ]);

        return back()->with('status', "Badge '{$validated['label']}' added to {$user->name} successfully.");
    }

    public function removeBadge(User $user, UserBadge $badge)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        if ($badge->user_id !== $user->id) abort(404);
        
        $label = $badge->label;
        $badge->delete();
        
        return back()->with('status', "Badge '{$label}' removed successfully.");
    }
