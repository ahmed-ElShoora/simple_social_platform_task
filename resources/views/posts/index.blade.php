<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <a href="{{ route('posts.create') }}" class="btn btn-success mb-3">Create New Post</a>
                    <table class="table" style="width: 100%;text-align: center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" style="width: 50%">Content</th>
                                <th scope="col">type</th>
                                <th scope="col">Likes</th>
                                <th scope="col">Comments</th>
                                <th scope="col">update</th>
                                <th scope="col">delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <th scope="row">{{$post->id}}</th>
                                    <td style="width: 50%;text-align: left">{{$post->content}}</td>
                                    <td>{{$post->type == "text" ? "Text" : "Text and Image"}}</td>
                                    <td><a href="{{ route('posts.likes', $post->id) }}" class="btn btn-primary">{{$post->likes_count}}</a></td>
                                    <td><a href="{{ route('posts.comments', $post->id) }}" class="btn btn-info">{{$post->comments_count}}</a></td>
                                    <td><a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">update</a></td>
                                    <td>
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
