<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\LinkClick;

class ClickTracked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $clickData;

    /**
     * Create a new event instance.
     */
    public function __construct(LinkClick $click)
    {
        $this->clickData = [
            'type' => 'click',
            'link_id' => $click->link_id,
            'user_id' => $click->link->user_id,
            'earnings' => $click->earnings_generated,
            'country' => $click->country_code,
            'city' => $click->city,
            'timestamp' => $click->clicked_at->toISOString(),
            'is_unique' => $click->is_unique,
            'ip_address' => $click->ip_address,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->clickData['user_id']),
            new Channel('global-clicks'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return $this->clickData;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'click-tracked';
    }
}
