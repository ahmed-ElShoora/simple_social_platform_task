<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
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
    public function store(CreateCommentRequest $request)
    {
        $validated = $request->validated();
        $comment = auth()->user()->comments()->create($validated);
        return $this->successResponse($comment, 'Comment created successfully');
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
        $comment = Comment::find($id);

        if(!$comment) {
            return $this->errorResponse('Comment not found', null, 404);
        }

        if ($comment->user_id !== $me->id) {
            abort(403);
        }

        $comment->delete();

        return $this->successResponse(null, 'Comment deleted successfully');
    }
}
