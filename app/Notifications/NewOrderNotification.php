<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;
use Illuminate\Notifications\Messages\MailMessage;
use Lang;
class NewOrderNotification extends Notification
{
    public $order;

    function __construct($order)
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
        return ['mail','database',OneSignalChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "messages" => "New order from {$notifiable->profile->firstname} {$notifiable->profile->surname} ",
            // "order" => "{$this->order}",
        ];
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
               return (new MailMessage)
                   ->subject(Lang::get('messages.new_order'))
                   ->line(Lang::get('messages.logistic_order_line'))
                     ->view('emails.logistic_new_order', compact('order'))
//                     ->action('Notification Action', url('/'))
//                    ->to($this->order->user()->email)
                    ->line(Lang::get('messages.thanks'));
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->subject("New Order!")
            ->body("Order from {$this->order->user->profile->firstname}.")
            ->url(config('services.admin_front_end.url')."order/{$this->order->reference}");
    }
}
