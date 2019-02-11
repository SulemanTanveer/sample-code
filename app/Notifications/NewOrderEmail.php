<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Lang;
class NewOrderEmail extends Notification
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
                    ->subject(Lang::get('messages.new_order'))
//                    ->line(Lang::get('messages.user_order_line'))
                    ->view('emails.order_placed', compact('order'));
                    // ->action('Notification Action', url('/'))
//                    ->line(Lang::get('messages.thank_user'));
    }

}
