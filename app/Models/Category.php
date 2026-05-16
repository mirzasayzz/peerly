<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'color', 'sort_order'];

    public function forums()
    {
        return $this->hasMany(Forum::class);
    }

    public function getPostsCountAttribute(): int
    {
        return Post::whereIn('forum_id', $this->forums()->pluck('id'))->count();
    }
}
