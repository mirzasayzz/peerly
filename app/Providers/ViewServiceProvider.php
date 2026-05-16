<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.sidebar', function ($view) {
            $view->with('sidebarCategories', Category::withCount('forums')->orderBy('sort_order')->get());
            $view->with('sidebarTags', Tag::withCount('posts')->orderByDesc('posts_count')->take(10)->get());
        });
    }
}
