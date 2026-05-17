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
Route::get('/p/{slug}', [PostController::class, 'show'])->name('posts.show');
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
    // Admin Guest Routes
    Route::middleware('guest')->group(function () {
        Route::get('login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.submit');
    });

    // Admin Authenticated Routes
    Route::middleware('auth')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::post('/manage-admins', function (\Illuminate\Http\Request $request) {
            if (auth()->user()->email !== 'tubamirza822@gmail.com') abort(403);
            $request->validate(['email' => 'required|email']);
            
            $user = \App\Models\User::where('email', $request->email)->first();
            
            if (!$user) {
                // Auto-generate a username
                $baseUsername = \Illuminate\Support\Str::slug(explode('@', $request->email)[0], '_');
                $username = preg_replace('/[^A-Za-z0-9_]/', '', $baseUsername);
                $counter = 1;
                while (\App\Models\User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter++;
                }

                $user = \App\Models\User::create([
                    'name' => 'New Admin',
                    'email' => $request->email,
                    'username' => $username,
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]);

                // Generate Password Reset Token
                $token = \Illuminate\Support\Facades\Password::getRepository()->create($user);
                $resetLink = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));

                // Send Welcome Email
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\AdminWelcomeMail($resetLink));
                
                return back()->with('status', 'New admin account created and welcome email sent successfully!');
            } else {
                $user->role = 'admin';
                $user->save();
                return back()->with('status', 'Existing user promoted to admin successfully.');
            }
        })->name('manage.add');

        Route::delete('/manage-admins/{user}', function (\App\Models\User $user) {
            if (auth()->user()->email !== 'tubamirza822@gmail.com') abort(403);
            if ($user->email === 'tubamirza822@gmail.com') abort(403, 'Cannot remove super admin.');
            $user->role = 'student'; // Demote back to student
            $user->save();
            return back()->with('status', 'Admin removed successfully.');
        })->name('manage.remove');

        Route::get('/users', [\App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('users.index');
        Route::get('/posts', [\App\Http\Controllers\Admin\DashboardController::class, 'posts'])->name('posts.index');
        Route::get('/comments', [\App\Http\Controllers\Admin\DashboardController::class, 'comments'])->name('comments.index');

        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\DashboardController::class, 'deleteUser'])->name('users.destroy');
        Route::delete('/posts/{post}', [\App\Http\Controllers\Admin\DashboardController::class, 'deletePost'])->name('posts.destroy');
        Route::post('/posts/{post}/pin', [\App\Http\Controllers\Admin\DashboardController::class, 'togglePinPost'])->name('posts.pin');
        Route::delete('/comments/{comment}', [\App\Http\Controllers\Admin\DashboardController::class, 'deleteComment'])->name('comments.destroy');
    });
});

require __DIR__.'/auth.php';
