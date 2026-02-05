<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Storage;
use App\Traits\ApiResponse;
use App\Services\UpdateProfileService;

class ProfileController extends Controller
{
    use ApiResponse;
    public function search(Request $request)
    {
        $query = $request->name;
        if(!$query){
            $users_profiles = User::with('profile')->get();
        }else{
            $users_profiles = User::with('profile')
            ->where('name', 'LIKE', "%{$query}%")
            ->get();
        }

        return $this->successResponse($users_profiles, 'Search results retrieved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_profile = User::with('profile')->find($id);

        if (!$user_profile) {
            return $this->errorResponse('User profile not found',null,  404);
        }

        return $this->successResponse($user_profile, 'User profile retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, string $id, UpdateProfileService $updater)
    {
        $user = $request->user();

        if ($user->id !== (int) $id) {
            return $this->errorResponse('Unauthorized to update this profile', null, 403);
        }

        $updatedUser = $updater->update(
            $user,
            $request->validated(),
            $request->file('profile_picture')
        );

        return $this->successResponse($updatedUser, 'Profile updated successfully');
    }
}
