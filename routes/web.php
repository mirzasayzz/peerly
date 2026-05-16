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
Route::middleware(['auth', 'verified'])->group(function () {
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

// Admin
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
