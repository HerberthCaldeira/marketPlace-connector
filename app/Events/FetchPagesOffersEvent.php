<?php

namespace App\Events;

use App\Models\ImportTask;
use App\Models\ImportTaskPage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
/**
 * It's responsible for dispatching the event to fetch the offers from all pages of the import task. 
 * 
 * @see App\Listeners\FetchPagesOffersListener
 */
class FetchPagesOffersEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     * 
     * @param ImportTask $importTask
     * 
     */
    public function __construct(
        public ImportTask $importTask
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
