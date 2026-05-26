<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminInvitation;
use App\Models\Post;
use App\Models\User;
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
        $users = User::orderBy('created_at', 'desc')->paginate(15);
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

    public function deletionRequests()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $requests = \App\Models\AccountDeletionRequest::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.deletion-requests', compact('requests'));
    }

    public function approveDeletionRequest(\App\Models\AccountDeletionRequest $deletionRequest)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $user = $deletionRequest->user;
        if ($user->email === 'tubamirza822@gmail.com') {
            abort(403, 'Cannot delete super admin.');
        }

        $user->delete();

        return back()->with('status', 'Account deleted successfully.');
    }

    public function rejectDeletionRequest(\App\Models\AccountDeletionRequest $deletionRequest)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $deletionRequest->delete();

        return back()->with('status', 'Deletion request rejected.');
    }
}
