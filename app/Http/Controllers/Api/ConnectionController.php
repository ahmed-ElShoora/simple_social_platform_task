<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Connection;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Requests\SendConnectionRequest;

class ConnectionController extends Controller
{
    use ApiResponse;

    public function send(SendConnectionRequest $request)
    {
        $validatedData = $request->validated();

        $me = auth()->id();
        $other = $validatedData['user_id'];

        if ($me === $other) {
            return $this->errorResponse('لا يمكنك إرسال طلب صداقة لنفسك', null, 422);
        }

        $low  = min($me, $other);
        $high = max($me, $other);
        $action = ($me === $low) ? 'low' : 'high';

        $conn = Connection::where('user_id_low', $low)
            ->where('user_id_high', $high)
            ->first();

        if ($conn && in_array($conn->status, ['pending', 'accepted'])) {
            return $this->errorResponse('طلب الصداقة موجود بالفعل', null, 422);
        }

        $conn = Connection::updateOrCreate(
            ['user_id_low' => $low, 'user_id_high' => $high],
            ['status' => 'pending', 'action_user' => $action]
        );

        return $this->successResponse($conn, 'Friend request sent successfully');
    }

    public function accept($id)
    {
        $me = auth()->id();
        $conn = Connection::findOrFail($id);

        if (!in_array($me, [$conn->user_id_low, $conn->user_id_high])) {
            abort(403);
        }

        if ($conn->status !== 'pending') {
            return $this->errorResponse('الطلب مش Pending', 422);
        }

        $initiatorId = ($conn->action_user === 'low')
            ? $conn->user_id_low
            : $conn->user_id_high;

        if ($me === $initiatorId) {
            return $this->errorResponse('إنت اللي باعت الطلب', 403);
        }

        $conn->update(['status' => 'accepted']);

        return $this->successResponse($conn, 'Connection accepted successfully');
    }

    public function reject($id)
    {
        $me = auth()->id();
        $conn = Connection::findOrFail($id);

        if (!in_array($me, [$conn->user_id_low, $conn->user_id_high])) {
            abort(403);
        }

        if ($conn->status !== 'pending') {
            return $this->errorResponse('الطلب مش Pending', 422);
        }

        $initiatorId = ($conn->action_user === 'low')
            ? $conn->user_id_low
            : $conn->user_id_high;

        if ($me === $initiatorId) {
            return $this->errorResponse('إنت اللي باعت الطلب', 403);
        }

        $conn->update(['status' => 'rejected']);

        return $this->successResponse($conn, 'Connection rejected successfully');
    }

    public function incoming()
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

        return $this->successResponse($incoming, 'Incoming friend requests retrieved successfully');
    }

    public function friends()
    {
        $me = auth()->id();

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

        return $this->successResponse($connections, 'Friends retrieved successfully');
    }
}
