<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    protected $message;
    protected $data;

    public function __construct(string $message, array $data = [])
    {
        $this->message = $message;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return array_merge([
            'message' => $this->message,
            'type' => $this->data['type'] ?? 'custom',
            'timestamp' => now(),
        ], $this->data);
    }
}

// $message = 'User ' . $user->name . ' was deleted.';
// $data = [
//     'type' => 'delete',
//     'user_id' => $user->id,
//     'user_name' => $user->name,
//     'user_email' => $user->email,
// ];

// auth()->user()->notify(new NewMessageNotification($message, $data));
// broadcast(new NewNotificationEvent(auth()->id(), $data));