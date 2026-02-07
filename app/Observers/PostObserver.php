<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        $post->likes()->delete();
        $post->comments()->delete();
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $post->likes()->delete();
        $post->comments()->delete();
    }
}
