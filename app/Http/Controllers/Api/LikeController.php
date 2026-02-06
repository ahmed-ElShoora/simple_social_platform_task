<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateLikeRequest;
use App\Traits\ApiResponse;
use App\Models\Like;

class LikeController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLikeRequest $request)
    {
        $validated = $request->validated();
        $like = auth()->user()->likes()->create($validated);
        return $this->successResponse($like, 'Like created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $me = auth()->user();
        $like = Like::find($id);
        if(!$like) {
            return $this->errorResponse('Like not found', null, 404);
        }
        if ($like->user_id !== $me->id) {
            abort(403);
        }
        $like->delete();
        return $this->successResponse(null, 'Like removed successfully');
    }
}
