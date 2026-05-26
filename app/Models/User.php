<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'bio',
        'university',
        'major',
        'year',
        'username_changed_at',
        'role',
        'reputation',
        'last_seen_at',
        'github',
        'linkedin',
        'website',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- Relationships ---

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                     ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                     ->withTimestamps();
    }

    // --- Helpers ---

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return in_array($this->role, ['admin', 'moderator']);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && strlen($this->avatar) > 1) {
            if (str_starts_with($this->avatar, 'http')) {
                return $this->avatar;
            }
            // Only generate URL for valid-looking paths (e.g. avatars/filename.jpg)
            if (str_contains($this->avatar, '/')) {
                return \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->url($this->avatar);
            }
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=7c5cfc&color=fff&size=128';
    }

    public function getReputationBadgeAttribute(): array
    {
        return match (true) {
            $this->reputation >= 1000 => ['label' => 'Legend', 'icon' => '👑', 'color' => '#ffd700'],
            $this->reputation >= 500 => ['label' => 'Expert', 'icon' => '💎', 'color' => '#00d4ff'],
            $this->reputation >= 200 => ['label' => 'Active', 'icon' => '🔥', 'color' => '#ff6b35'],
            $this->reputation >= 50 => ['label' => 'Contributor', 'icon' => '⭐', 'color' => '#7c5cfc'],
            default => ['label' => 'Newcomer', 'icon' => '🌱', 'color' => '#4ade80'],
        };
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function hasBookmarked(Post $post): bool
    {
        return $this->bookmarks()->where('post_id', $post->id)->exists();
    }
}
