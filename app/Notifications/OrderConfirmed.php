<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class OrderConfirmed extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      $order = $this->order;
      $d_date = Carbon::createFromFormat('d/m/y',$order->created_at)->addWeekdays(3)->format('d/m/y');
      $order->delivery_date = $d_date;
        return (new MailMessage)
                    ->subject('Confirmation de commande')
                    ->attach(env('BACKEND_URL').'/api/v1/order/invoice/'.$this->order->reference,[
                        'as' => 'Facture-rentree-zen.pdf',
                        'mime' => 'application/pdf',
                    ])
//                    ->line('Your Order has been Confirmed.')
                    ->view('emails.order-confirmed',compact('order'))
//                    ->line('Thank you for Shopping!');
        ;
    }
}
