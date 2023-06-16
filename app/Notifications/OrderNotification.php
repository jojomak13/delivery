<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class OrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $data = [
            'title' => __('app.order.notification.title', ['store' => $this->order->store->name], 'ru'),
            'body' => __('app.order.notification.body', ['status' => __('app.' . $this->order->status)], 'ru')
        ];

        Http::asForm()
            ->withHeaders(['Authorization' => 'key=' . config('services.fcm.server_token')])
            ->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $this->order->user->fc_token,
                'notification' => [
                    ...$data,
                    'content_available' => true,
                    'priority' => 'high'
                ]
            ]);
        

        return $data;
    }
}
