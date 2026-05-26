<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'post_id', 'parent_id', 'body', 'image_path', 'is_solution', 'depth'];

    protected function casts(): array
    {
        return [
            'is_solution' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::deleting(function ($comment) {
            // Delete polymorphic votes & reports
            $comment->votes()->delete();
            $comment->reports()->delete();

            if ($comment->image_path) {
                try {
                    \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->delete($comment->image_path);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to delete comment image from storage on model delete: ' . $e->getMessage());
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

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
