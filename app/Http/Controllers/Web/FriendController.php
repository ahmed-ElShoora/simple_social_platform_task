<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Connection;

class FriendController extends Controller
{
    public function index()
    {
        $me = auth()->id();

        $incoming = Connection::query()
        ->where('status', 'pending')
        ->where(function ($q) use ($me) {
            $q->where('user_id_low', $me)
            ->orWhere('user_id_high', $me);
        })
        ->where(function ($q) use ($me) {
            $q->where(function ($qq) use ($me) {
                $qq->where('action_user', 'low')
                ->where('user_id_low', '!=', $me);
            })->orWhere(function ($qq) use ($me) {
                $qq->where('action_user', 'high')
                ->where('user_id_high', '!=', $me);
            });
        })
        ->with(['userLow.profile', 'userHigh.profile'])
        ->get()
        ->map(function ($c) use ($me) {
            $otherUser = ($c->user_id_low == $me) ? $c->userHigh : $c->userLow;

            return [
                'id'         => $c->id,
                'status'     => $c->status,
                'created_at' => $c->created_at,
                'user_data'  => $otherUser, 
            ];
        });

        $connections = Connection::query()
            ->where('status', 'accepted')
            ->where(function ($q) use ($me) {
                $q->where('user_id_low', $me)
                  ->orWhere('user_id_high', $me);
            })
            ->with(['userLow.profile', 'userHigh.profile'])
            ->get()->map(function ($c) use ($me) {
            $otherUser = ($c->user_id_low == $me) ? $c->userHigh : $c->userLow;

            return [
                'id'         => $c->id,
                'status'     => $c->status,
                'created_at' => $c->created_at,
                'user_data'  => $otherUser, 
            ];
        });

        return view('friends.index', compact('incoming', 'connections'));
    }

    public function accept($id)
    {
        $me = auth()->id();
        $conn = Connection::findOrFail($id);

        if (!in_array($me, [$conn->user_id_low, $conn->user_id_high])) {
            abort(403);
        }

        if ($conn->status !== 'pending') {
            return redirect()->route('ui.friends.index')->with('error', 'الطلب مش Pending');
        }

        $initiatorId = ($conn->action_user === 'low')
            ? $conn->user_id_low
            : $conn->user_id_high;

        if ($me === $initiatorId) {
            return redirect()->route('ui.friends.index')->with('error', 'إنت اللي باعت الطلب');
        }

        $conn->update(['status' => 'accepted']);

        return redirect()->route('ui.friends.index')->with('success', 'Friend request accepted successfully');
    }

    public function reject($id)
    {
        $me = auth()->id();
        $conn = Connection::findOrFail($id);

        if (!in_array($me, [$conn->user_id_low, $conn->user_id_high])) {
            abort(403);
        }

        if ($conn->status !== 'pending') {
            return redirect()->route('ui.friends.index')->with('error', 'الطلب مش Pending');
        }

        $initiatorId = ($conn->action_user === 'low')
            ? $conn->user_id_low
            : $conn->user_id_high;

        if ($me === $initiatorId) {
            return redirect()->route('ui.friends.index')->with('error', 'إنت اللي باعت الطلب');
        }

        $conn->update(['status' => 'rejected']);

        return redirect()->route('ui.friends.index')->with('success', 'Friend request rejected successfully');
    }
}
