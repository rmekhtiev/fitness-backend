<?php


namespace App\Notifications\Channels;

use App\Notifications\Messages\SmsMessage;
use GuzzleHttp\Client;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;

use App\SigmaSms\Client as SigmaSmsClient;

class SigmaSmsChannel
{

    /** @var SigmaSmsClient */
    private $client;

    private $from;

    public function __construct(SigmaSmsClient $client, $from)
    {
        $this->client = $client;
        $this->from = $from;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('sigmasms', $notification)) {
            return;
        }

        /** @var string|SmsMessage $message */
        $message = $notification->toSigmaSms($notifiable);

        if (is_string($message)) {
            $message = new NexmoMessage($message);
        }

        $payload = [
            'recipient' => $to,
            'type' => 'sms',
            'payload' => [
                'sender' => $message->from ?: $this->from,
                'text' => trim($message->content),
            ]
        ];

        $this->client->send($payload);
    }
}
