<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('People') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('ui.peoples.query') }}" method="post">
                        @csrf
                        <input type="text" name="query" placeholder="Search for people..." class="border rounded px-3 py-2 w-full">
                    </form>
                    <table class="table" style="width: 100%;text-align: center">
                        <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">name</th>
                                <th scope="col">bio</th>
                                <th scope="col">profile image</th>
                                <th scope="col">actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users_profiles as $user_profile)
                                <tr>
                                    <th scope="row">{{$user_profile->id}}</th>
                                    <td>{{$user_profile->name}}</td>
                                    <td>{{$user_profile->profile->bio ?? 'No bio available'}}</td>
                                    <td>
                                        <img src="{{ Storage::url($user_profile->profile->profile_picture) }}" alt="Profile Image" class="w-16 h-16 rounded-full object-cover" style="margin: auto">
                                    </td>
                                    <td>
                                        <a href="{{ route('ui.peoples.connect', $user_profile->id) }}" class="btn btn-primary">Connect</a>
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
