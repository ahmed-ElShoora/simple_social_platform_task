<?php

namespace App\Observers;

use App\Models\Connection;
use App\Events\FriendSend;

class ConnectionObserver
{
    /**
     * Handle the Connection "created" event.
     */
    public function created(Connection $connection): void
    {
        if($connection->action_user == "low"){
            $send_notification_to = $connection->user_id_high;
            $name = $connection->userHigh->name;
        }else{
            $send_notification_to = $connection->user_id_low;
            $name = $connection->userLow->name;
        }

        FriendSend::dispatch($send_notification_to, [
            'actor_name' => $name,
        ]);
    }

    /**
     * Handle the Connection "updated" event.
     */
    public function updated(Connection $connection): void
    {
        //
    }

    /**
     * Handle the Connection "deleted" event.
     */
    public function deleted(Connection $connection): void
    {
        //
    }

    /**
     * Handle the Connection "restored" event.
     */
    public function restored(Connection $connection): void
    {
        //
    }

    /**
     * Handle the Connection "force deleted" event.
     */
    public function forceDeleted(Connection $connection): void
    {
        //
    }
}
