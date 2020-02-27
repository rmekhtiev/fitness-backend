<?php

namespace App\Notifications;

use App\Models\Client;
use App\Notifications\Messages\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionGained extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param Client $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable->phone_number ? ['sigmasms'] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param Client $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the SigmaSMS representation of the notification.
     *
     * @param Client $notifiable
     * @return SmsMessage
     */
    public function toSigmaSms($notifiable)
    {
        return (new SmsMessage)
            ->content("Это тестовое сообщение.\nИзменённый текст будет отправлен на модерацию.")
            ->unicode();
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
