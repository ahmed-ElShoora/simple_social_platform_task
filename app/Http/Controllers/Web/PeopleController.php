<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProfilesService;
use App\Models\User;
use App\Models\Connection;

class PeopleController extends Controller
{
    public function index(ProfilesService $profilesService)
    {
        $users_profiles = $profilesService->getProfileWithOutConnections(auth()->user(), '');
        return view('people.index',compact('users_profiles'));
    }

    public function query(Request $request, ProfilesService $profilesService)
    {
        $validated = $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $query = $validated['query'];
        $users_profiles = $profilesService->getProfileWithOutConnections($request->user(), $query);
        return view('people.index', compact('users_profiles')); 
    }

    public function connect(User $user)
    {

        $me = auth()->id();
        $other = $user->id;

        if ($me === $other) {
            return redirect()->route('ui.peoples.index')->with('error', 'لا يمكنك إرسال طلب صداقة لنفسك.');
        }

        $low  = min($me, $other);
        $high = max($me, $other);
        $action = ($me === $low) ? 'low' : 'high';

        $conn = Connection::where('user_id_low', $low)
            ->where('user_id_high', $high)
            ->first();

        if ($conn && in_array($conn->status, ['pending', 'accepted'])) {
            return redirect()->route('ui.peoples.index')->with('error', 'طلب الصداقة موجود بالفعل');
        }

        $conn = Connection::updateOrCreate(
            ['user_id_low' => $low, 'user_id_high' => $high],
            ['status' => 'pending', 'action_user' => $action]
        );
        
        return redirect()->route('ui.peoples.index')->with('success', 'Friend request sent.');
    }
}
