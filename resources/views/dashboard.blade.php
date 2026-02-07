<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feeds') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($posts->isEmpty())
                        <p class="text-gray-500">No posts available in your feed.</p>
                    @else
                        @foreach($posts as $post)
                            <div class="mb-4 p-4 border border-gray-200 rounded" style="width: 50%;margin:auto;">
                                <div style="display: flex;direction:row;justify-content: space-between;align-items: center;">
                                    <h3 class="font-semibold text-lg">
                                        <span class="inline-block w-8 h-8 rounded-full overflow-hidden mr-2">
                                            <img src="{{Storage::url($post->user->profile->profile_picture)}}" style="width: 100%;height:100%;object-fit:cover">
                                        </span>
                                        {{ $post->user->name }}
                                    </h3>
                                    <small class="text-gray-500">{{ $post->created_at }}</small>
                                </div>
                                <p>{{ $post->content }}</p>
                                @if($post->type !== "text")
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($post->image) }}" alt="Post Media" class="max-w-full h-auto rounded">
                                    </div>
                                @endif
                                    
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
