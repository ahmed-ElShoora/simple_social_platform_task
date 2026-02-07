<?php

namespace App\Observers;

use App\Models\Comment;
use App\Events\CommentSet;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        $postOwnerId = $comment->post->user_id;
        if ($postOwnerId === $comment->user_id) return;

        CommentSet::dispatch($postOwnerId, [
            'comment' => $comment->content,
            'actor_name' => $comment->user->name,
        ]);
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
