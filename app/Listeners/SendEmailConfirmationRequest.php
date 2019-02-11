<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserWasRegistered;
use App\Mail\UserCreated;
use Mail;

class SendEmailConfirmationRequest implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        Mail::to($event->user)->send(new UserCreated($event->user));   
    }
}
