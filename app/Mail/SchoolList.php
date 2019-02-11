<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class SchoolList extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    public $file;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request, $file)
    {
        $this->request = $request;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.school-list')
                    ->from(env('MAIL_SENDER_ADDRESS'),env('MAIL_SENDER_USERNAME'))
                    ->with(['data' => $this->request])
                    ->subject($this->message())
                    ->attach($this->file);
    }
    public function message()
    {
        return 'Une nouvelle liste de fournitures scolaire a été envoyée.';
    }
}
