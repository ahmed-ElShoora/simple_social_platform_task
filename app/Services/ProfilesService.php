<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfilesService
{
    public function getProfileWithOutConnections(User $user, string $query = ''): User
    {
        $me = $user->id;
        $users_profiles = User::with('profile')
        ->where('id', '!=', $me)
        ->whereDoesntHave('connectionsAsLow', function ($q) use ($me) {
            $q->where('user_id_low', $me);
        })
        ->whereDoesntHave('connectionsAsHigh', function ($q) use ($me) {
            $q->where('user_id_high', $me);
        });
        if(empty($query)) {
            $users_profiles->get();
        }else{
            $users_profiles->where('name', 'LIKE', "%{$query}%")->get();
        }
        return $users_profiles;
    }
}
