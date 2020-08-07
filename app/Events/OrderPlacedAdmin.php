<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderPlacedAdmin implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $orderPlacedAdmin;
    public function __construct($orderPlacedAdmin)
    {
        $this->orderPlacedAdmin = $orderPlacedAdmin;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['order-placed-admin'.$this->orderPlacedAdmin->rec_id];
    }
}
