<?php

declare(strict_types = 1);

namespace App\Domains\Hub\Jobs;

use App\Domains\Hub\Services\HubService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendOfferToHubJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(HubService $hubService): void
    {
        logger('Send offer to hub', ['data' => $this->data]);
        $hubService->sendOffer([]);
    }

    public function failed($exception): void
    {
        Log::error('SendOfferToHubJob::Error sending offer to hub', ['data' => $this->data, 'error' => $exception->getMessage()]);
    }

    public function tags(): array
    {
        return ['SendOfferToHubJob'];
    }
}
