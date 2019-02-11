<?php

namespace App\Mail;

use http\Env\Request;
use App\Models\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invoice extends Mailable
{
    use Queueable, SerializesModels;
    
    protected $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->order;
        return $this->view('emails.order-invoice')
                    ->with([
                        'order' => $order,
                        'user'=>$order->user()->first(),
                        'address'=>$order->delivery_address()->first()
                    ])
                    ->subject('Rentrée ZEN: Confirmation de votre commande')
//            ->attachData('')
                    ->attach(env('BACKEND_URL').'/api/v1/order/invoice/'.$order->reference, [
                'as' => 'Facture-rentree-zen.pdf',
                'mime' => 'application/pdf',
            ])
                    ->from(
                        env('MAIL_SENDER_ADDRESS'),
                        env('MAIL_SENDER_USERNAME')
                    )
            ;
    }
}
