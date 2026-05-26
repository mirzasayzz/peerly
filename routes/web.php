<?php

use App\Http\Controllers\{
    HomeController,
    ForumController,
    PostController,
    CommentController,
    VoteController,
    BookmarkController,
    SearchController,
    TagController,
    UserProfileController,
    NotificationController,
    ProfileController,
};
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/forums', [ForumController::class, 'index'])->name('forums.index');
Route::get('/f/{forum:slug}', [ForumController::class, 'show'])->name('forums.show');
Route::get('/p/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/@{username}', [UserProfileController::class, 'show'])->name('profile.show');

// Auth & Verified Routes (Users must confirm email to post)
Route::middleware(['auth'])->group(function () {
    // Posts CRUD
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/p/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/p/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/p/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Comments
    Route::post('/p/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/solution', [CommentController::class, 'markSolution'])->name('comments.solution');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Voting & Bookmarks (AJAX)
    Route::post('/vote', [VoteController::class, 'toggle'])->name('vote.toggle');
    Route::post('/bookmark', [BookmarkController::class, 'toggle'])->name('bookmark.toggle');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

    // Follow
    Route::post('/follow/{user}', [UserProfileController::class, 'follow'])->name('follow.toggle');
});

// Auth-only Routes (Can be accessed before verifying email)
Route::middleware('auth')->group(function () {
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Profile Settings (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Guest Routes (no auth required)
    Route::middleware('guest')->group(function () {
        Route::get('login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.submit');
    });

    // Admin Onboarding (public — invitee is not logged in yet)
    Route::get('onboard/{token}', [\App\Http\Controllers\Admin\OnboardingController::class, 'show'])->name('onboard.show');
    Route::post('onboard/{token}', [\App\Http\Controllers\Admin\OnboardingController::class, 'complete'])->name('onboard.complete');

    // Admin Authenticated Routes
    Route::middleware('auth')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::post('/manage-admins', function (\Illuminate\Http\Request $request) {
            if (auth()->user()->email !== 'tubamirza822@gmail.com') abort(403);
            $request->validate(['email' => 'required|email']);

            // Check if user already exists and is already an admin
            $existingUser = \App\Models\User::where('email', $request->email)->first();
            if ($existingUser && $existingUser->role === 'admin') {
                return back()->with('status', 'This user is already an admin.');
            }

            // Remove any old pending invitation for this email
            \App\Models\AdminInvitation::where('email', $request->email)->delete();

            // Create a fresh invitation token (expires in 48 hours)
            $token = \Illuminate\Support\Str::random(64);
            \App\Models\AdminInvitation::create([
                'email'      => $request->email,
                'token'      => $token,
                'status'     => 'pending',
                'expires_at' => now()->addHours(48),
            ]);

            $onboardingLink = url('/admin/onboard/' . $token);

            // Send invitation email
            \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\AdminInviteMail($onboardingLink));

            return back()->with('status', 'Invitation email sent to ' . $request->email . '. They will receive a link to complete their onboarding.');
        })->name('manage.add');

        Route::delete('/manage-admins/{user}', function (\App\Models\User $user) {
            if (auth()->user()->email !== 'tubamirza822@gmail.com') abort(403);
            if ($user->email === 'tubamirza822@gmail.com') abort(403, 'Cannot remove super admin.');
            $user->role = 'student'; // Demote back to student
            $user->save();
            return back()->with('status', 'Admin removed successfully.');
        })->name('manage.remove');

        Route::delete('/manage-admins/invite/{invite}', function (\App\Models\AdminInvitation $invite) {
            if (auth()->user()->email !== 'tubamirza822@gmail.com') abort(403);
            $invite->delete();
            return back()->with('status', 'Pending invite revoked successfully.');
        })->name('manage.revoke');


        Route::get('/users', [\App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('users.index');
        Route::get('/posts', [\App\Http\Controllers\Admin\DashboardController::class, 'posts'])->name('posts.index');
        Route::get('/comments', [\App\Http\Controllers\Admin\DashboardController::class, 'comments'])->name('comments.index');
        Route::get('/deletion-requests', [\App\Http\Controllers\Admin\DashboardController::class, 'deletionRequests'])->name('deletion-requests.index');

        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\DashboardController::class, 'deleteUser'])->name('users.destroy');
        Route::delete('/posts/{post}', [\App\Http\Controllers\Admin\DashboardController::class, 'deletePost'])->name('posts.destroy');
        Route::post('/posts/{post}/pin', [\App\Http\Controllers\Admin\DashboardController::class, 'togglePinPost'])->name('posts.pin');
        Route::delete('/comments/{comment}', [\App\Http\Controllers\Admin\DashboardController::class, 'deleteComment'])->name('comments.destroy');
        Route::post('/deletion-requests/{deletionRequest}/approve', [\App\Http\Controllers\Admin\DashboardController::class, 'approveDeletionRequest'])->name('deletion-requests.approve');
        Route::post('/deletion-requests/{deletionRequest}/reject', [\App\Http\Controllers\Admin\DashboardController::class, 'rejectDeletionRequest'])->name('deletion-requests.reject');
    });
});

require __DIR__.'/auth.php';
