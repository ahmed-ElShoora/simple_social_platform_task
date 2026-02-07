<?php

namespace App\Services;

use App\Models\User;
use App\Models\Post;
use App\Models\Connection;

class FeedService
{
    public function getFeedForUser()
    {
        $me = auth()->id();

        $friendIds = Connection::query()
            ->where('status', 'accepted')
            ->where(function ($q) use ($me) {
                $q->where('user_id_low', $me)
                ->orWhere('user_id_high', $me);
            })
            ->get()
            ->map(fn ($c) => ($c->user_id_low == $me) ? $c->user_id_high : $c->user_id_low)
            ->values()
            ->all();

        $posts = Post::query()
            ->whereIn('user_id', $friendIds)
            ->latest() 
            ->with(['user.profile', 'comments.user.profile'])
            ->withCount('likes')
            ->get();
        return $posts;
    }
}
