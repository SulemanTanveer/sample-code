<?php

namespace App\Listeners;

use App\Events\NewOrder;
use App\Notifications\NewOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
Use App\User;
use Lang;
class NotifyLogisticManager implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  NewOrder  $event
     * @return void
     */
    public function handle(NewOrder $event)
    {
        $users = User::logisticManager()->get();
                $users->each(function($user) use ($event){
                $user->notify(new NewOrderNotification($event->order));
            });
    }
}
