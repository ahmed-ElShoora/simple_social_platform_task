<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Friends') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if(!$incoming->isEmpty())
                        <h3 class="text-lg font-semibold mb-4">Incoming Friend Requests</h3>
                        <table class="table" style="width: 100%;text-align: center">
                            <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">name</th>
                                    <th scope="col">bio</th>
                                    <th scope="col">profile image</th>
                                    <th scope="col">accept</th>
                                    <th scope="col">reject</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($incoming as $incoming_request)
                                    <tr>
                                        <th scope="row">{{$incoming_request['id']}}</th>
                                        <td>{{$incoming_request['user_data']->name}}</td>
                                        <td>{{$incoming_request['user_data']->profile->bio ?? 'No bio available'}}</td>
                                        <td>
                                            <img src="{{ Storage::url($incoming_request['user_data']->profile->profile_picture) }}" alt="Profile Image" class="w-16 h-16 rounded-full object-cover" style="margin: auto">
                                        </td>
                                        <td>
                                            <a href="{{ route('ui.friends.accept', $incoming_request['id']) }}" class="btn btn-success">Accept</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('ui.friends.reject', $incoming_request['id']) }}" class="btn btn-danger">Reject</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    @if(!$connections->isEmpty())
                        <h3 class="text-lg font-semibold mb-4">My Friends</h3>
                        <table class="table" style="width: 100%;text-align: center">
                            <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">name</th>
                                    <th scope="col">bio</th>
                                    <th scope="col">profile image</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($connections as $connection)
                                    <tr>
                                        <th scope="row">{{$connection['id']}}</th>
                                        <td>{{$connection['user_data']->name}}</td>
                                        <td>{{$connection['user_data']->profile->bio ?? 'No bio available'}}</td>
                                        <td>
                                            <img src="{{ Storage::url($connection['user_data']->profile->profile_picture) }}" alt="Profile Image" class="w-16 h-16 rounded-full object-cover" style="margin: auto">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
