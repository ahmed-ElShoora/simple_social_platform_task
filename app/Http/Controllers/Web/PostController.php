<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::where('user_id', auth()->id())->withCount('comments', 'likes')->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $validated['type'] = "text";
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
            $validated['type'] = "has-image";
        }
        $post = auth()->user()->posts()->create($validated);
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
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
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function likes(string $id)
    {
        $posts = Post::where('id', $id)->with('likes.user')->first();
        $likes = $posts->likes;
        return view('posts.likes', compact('likes'));
    }

    public function comments(string $id)
    {
        $posts = Post::where('id', $id)->with('comments.user')->first();
        $comments = $posts->comments;
        return view('posts.comments', compact('comments'));
    }
}
