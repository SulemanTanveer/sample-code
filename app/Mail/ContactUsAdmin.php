<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUsAdmin extends Mailable
{
    use Queueable, SerializesModels;

        public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->request->email)->view('emails.contact-us-for-admin')
                    ->with(['data' => $this->request])
                    ->subject('RENTREE ZEN: Vous avez reçu un nouveau message');
    }
    public function message()
    {
        return '';
    }
}
