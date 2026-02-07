<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Post;
use App\Observers\PostObserver;
use App\Models\Comment;
use App\Observers\CommentObserver;
use App\Models\Like;
use App\Observers\LikeObserver;
use App\Models\Connection;
use App\Observers\ConnectionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);
        Like::observe(LikeObserver::class);
        Connection::observe(ConnectionObserver::class);
    }
}
