<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Traits\ApiResponse;
use App\Models\Connection;

class FeedController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
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

        return $this->successResponse($posts, 'Feed retrieved successfully');
    }
}
