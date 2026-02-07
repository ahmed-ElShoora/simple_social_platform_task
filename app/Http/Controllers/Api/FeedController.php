<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Traits\ApiResponse;
use App\Models\Connection;
use App\Services\FeedService;

class FeedController extends Controller
{
    use ApiResponse;

    public function __construct(private FeedService $feedService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = $this->feedService->getFeedForUser();

        return $this->successResponse($posts, 'Feed retrieved successfully');
    }
}
