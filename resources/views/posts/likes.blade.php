<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post Likes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table" style="width: 100%;text-align: center">
                        <thead>
                            <tr>
                                <th scope="col">like id</th>
                                <th scope="col">name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($likes as $like)
                                <tr>
                                    <th scope="row">{{$like->id}}</th>
                                    <td>{{$like->user->name}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
