<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\BlogVisitor;

class BlogVisitorTracked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $visitorData;

    /**
     * Create a new event instance.
     */
    public function __construct(BlogVisitor $visitor)
    {
        $this->visitorData = [
            'type' => 'blog_visit',
            'blog_post_id' => $visitor->blog_post_id,
            'user_id' => $visitor->blogPost->user_id,
            'earnings' => $visitor->earnings_inr,
            'country' => $visitor->country_code,
            'city' => $visitor->city,
            'timestamp' => $visitor->visited_at->toISOString(),
            'time_spent' => $visitor->time_spent_seconds,
            'is_unique' => $visitor->is_unique_visit,
            'ip_address' => $visitor->ip_address,
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
            new PrivateChannel('user.' . $this->visitorData['user_id']),
            new Channel('global-blog-visits'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return $this->visitorData;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'blog-visitor-tracked';
    }
}
