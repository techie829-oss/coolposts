<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserEarning;

class EarningsTracked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $earningData;

    /**
     * Create a new event instance.
     */
    public function __construct(UserEarning $earning)
    {
        $this->earningData = [
            'type' => 'earning',
            'user_id' => $earning->user_id,
            'amount' => $earning->amount,
            'currency' => $earning->currency,
            'source' => $earning->link_id ? 'link' : 'blog',
            'timestamp' => $earning->created_at->toISOString(),
            'status' => $earning->status,
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
            new PrivateChannel('user.' . $this->earningData['user_id']),
            new Channel('global-earnings'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return $this->earningData;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'earnings-tracked';
    }
}
