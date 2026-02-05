<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateProfileService
{
    public function update(User $user, array $validatedData, ?UploadedFile $profilePicture = null): User
    {
        return DB::transaction(function () use ($user, $validatedData, $profilePicture) {

            if (Arr::has($validatedData, 'name')) {
                $user->name = $validatedData['name'];
                $user->save();
            }

            $profile = $user->profile;

            if (Arr::has($validatedData, 'bio')) {
                $profile->bio = $validatedData['bio'];
            }
            if ($profilePicture) {
                $this->replaceProfilePicture($profile, $profilePicture);
            }

            $profile->save();

            return $user->load('profile');
        });
    }

    protected function replaceProfilePicture($profile, UploadedFile $file): void
    {
        if ($profile->profile_picture && Storage::disk('public')->exists($profile->profile_picture)) {
            Storage::disk('public')->delete($profile->profile_picture);
        }
        $path = $file->store('profile_pictures', 'public');
        $profile->profile_picture = $path;
    }
}
