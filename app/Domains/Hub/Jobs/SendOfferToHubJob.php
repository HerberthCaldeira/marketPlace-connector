<?php

declare(strict_types = 1);

namespace App\Domains\Hub\Jobs;

use App\Domains\Hub\Services\HubService;
use App\Domains\Offers\States\SentToHubState;
use App\Models\ImportTaskOffer;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * It's responsible for sending an offer to the hub.
 * 
 *  
 */
class SendOfferToHubJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    /**
     * Create a new job instance.
     * 
     * @param ImportTaskOffer $importTaskOffer
     * 
     */
    public function __construct(public ImportTaskOffer $importTaskOffer)
    {
    }

    /**
     * Execute the job.
     * 
     * @param HubService $hubService
     * 
     */
    public function handle(HubService $hubService): void
    {
        logger("SendOfferToHubJob::Send offer to hub::{$this->importTaskOffer->reference}");
        $hubService->sendOffer($this->importTaskOffer->payload);
        $this->importTaskOffer->update(['status' => 'completed']);
        $this->importTaskOffer->setState(new SentToHubState($this->importTaskOffer));
    }

    /**
     * Handle a job failure.
     * 
     * @param \Throwable $exception
     * 
     */
    public function failed($exception): void
    {
        $this->importTaskOffer->update(['status' => 'failed']);
        Log::error('SendOfferToHubJob::Error sending offer to hub', ['data' => $this->importTaskOffer->payload, 'error' => $exception->getMessage()]);
    }

    public function tags(): array
    {
        return ['ImportTask::' . $this->importTaskOffer->import_task_id, 'ImportTaskPage::' . $this->importTaskOffer->import_task_page_id, 'SendOfferToHubJob::' . $this->importTaskOffer->reference];
    }
}
