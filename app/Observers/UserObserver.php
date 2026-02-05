<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $user->profile()->create([
            'bio' => "No bio yet",
            'profile_picture' => "default/profile-default.png",
        ]);
    }

    public function deleted(User $user): void
    {
        $user->profile()->delete();
        $user->posts()->delete();
        $user->comments()->delete();
        $user->likes()->delete();
    }
}
