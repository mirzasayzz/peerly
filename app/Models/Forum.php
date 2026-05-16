<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'icon', 'is_locked'];

    protected function casts(): array
    {
        return [
            'is_locked' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function latestPost()
    {
        return $this->hasOne(Post::class)->latestOfMany('last_activity_at');
    }
}
