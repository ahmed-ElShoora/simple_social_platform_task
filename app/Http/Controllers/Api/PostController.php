<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Traits\ApiResponse;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::where('user_id', auth()->id())->with('comments.user.profile', 'likes.user.profile')->get();
        return $this->successResponse($posts, 'Posts retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $validated['type'] = "text";
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
            $validated['type'] = "has-image";
        }
        $post = auth()->user()->posts()->create($validated);
        return $this->successResponse($post, 'Post created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::where('id', $id)->where('user_id', auth()->id())->with('comments.user.profile', 'likes.user.profile')->first();
        if (!$post) {
            return $this->errorResponse('Post not found', null, 404);
        }
        return $this->successResponse($post, 'Post retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $post = Post::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$post) {
            return $this->errorResponse('Post not found', null, 404);
        }
        $validated = $request->validated();
        if (isset($validated['content'])) {
            $post->content = $validated['content'];
        }
        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
            $post->image = $validated['image'];
        }
        $post->save();
        return $this->successResponse($post, 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$post) {
            return $this->errorResponse('Post not found', null, 404);
        }
        $post->delete();
        return $this->successResponse(null, 'Post deleted successfully');
    }
}
