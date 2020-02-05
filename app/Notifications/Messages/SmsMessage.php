<?php


namespace App\Notifications\Messages;

use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notifiable;

/**
 * @method  toSigmaSms(Notifiable $notifiable)
 */
class SmsMessage extends NexmoMessage
{

}
