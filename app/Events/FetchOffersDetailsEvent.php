<?php

declare(strict_types = 1);

namespace App\Events;

use App\Models\ImportTaskPage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * It's responsible for dispatching the event to fetch the details of an offer from the marketplace.
 *
 * @see App\Listeners\FetchOffersDetailsListener
 *
 */
class FetchOffersDetailsEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param ImportTaskPage $importTaskPage
     *
     */
    public function __construct(public ImportTaskPage $importTaskPage)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
