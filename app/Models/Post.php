<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'forum_id', 'title', 'slug', 'body',
        'image_path', 'is_pinned', 'is_locked', 'is_resolved',
        'view_count', 'last_activity_at',
    ];

    // Bind routes by slug instead of id
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
            'is_resolved' => 'boolean',
            'last_activity_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function rootComments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    // --- Accessors ---

    public function getVoteScoreAttribute(): int
    {
        return $this->votes()->sum('value');
    }

    public function getUserVoteAttribute(): ?int
    {
        if (!auth()->check()) return null;
        $vote = $this->votes()->where('user_id', auth()->id())->first();
        return $vote?->value;
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->url($this->image_path);
        }
        return null;
    }
}
