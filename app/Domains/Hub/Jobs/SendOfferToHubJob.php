<?php

declare(strict_types = 1);

namespace App\Domains\Hub\Jobs;

use App\Domains\Hub\Services\HubService;
use App\Models\ImportTaskOffer;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendOfferToHubJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(public ImportTaskOffer $importTaskOffer)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(HubService $hubService): void
    {
        $hubService->sendOffer($this->importTaskOffer->payload);
        $this->importTaskOffer->update(['status' => 'completed']);
    }

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
