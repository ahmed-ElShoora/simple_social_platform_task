<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function index()
    {
        return view('friends.index');
    }

    public function accept(Request $request, $userId)
    {
        // Logic to accept a friend request
        // You can use the authenticated user and the $userId to find the friend request and accept it
        // For example:
        // $friendRequest = FriendRequest::where('sender_id', $userId)->where('receiver_id', auth()->id())->first();
        // if ($friendRequest) {
        //     $friendRequest->status = 'accepted';
        //     $friendRequest->save();
        // }
        
        return redirect()->route('ui.friends.index')->with('success', 'Friend request accepted.');
    }

    public function reject(Request $request, $userId)
    {
        // Logic to reject a friend request
        // Similar to the accept method, but you would set the status to 'rejected' or delete the friend request
        
        return redirect()->route('ui.friends.index')->with('success', 'Friend request rejected.');
    }
}
