<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
         $this->user  = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_SENDER_ADDRESS'),env('MAIL_SENDER_USERNAME'))
            ->view('emails.password-reset-confirmation',['user'=>$this->user])
            ->subject('Rentrée ZEN: Votre mot de passe a été réinitialisé ');
    }
}
