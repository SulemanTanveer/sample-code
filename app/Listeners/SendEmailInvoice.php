<?php

namespace App\Listeners;

use App\Events\NewOrder;
use App\Notifications\NewOrderEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailInvoice implements ShouldQueue
{
    
    /**
     * Handle the event.
     *
     * @param  NewOrder  $event
     * @return void
     */
    public function handle(NewOrder $event)
    {
        $event->order->user->notify(new NewOrderEmail($event->order));
    }
}
