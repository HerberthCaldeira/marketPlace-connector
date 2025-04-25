<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Hub\Jobs\SendOfferToHubJob;
use App\Domains\Offers\Services\ImportOffersService;
use App\Models\ImportTaskOffer;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ImportOfferJob implements ShouldQueue
{
    use  Batchable;
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public ImportTaskOffer $importTaskOffer)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(ImportOffersService $importOffersService): void
    {
        logger("ImportOfferJob::handle::{$this->importTaskOffer->reference}");
        $data = $importOffersService->getOffer($this->importTaskOffer->reference);
        $this->importTaskOffer->update(['payload' => $data['data'], 'status' => 'fetched']);   
    }

    public function failed($exception): void
    {
        $this->importTaskOffer->update(['status' => 'failed']);
        Log::error(
            'ImportOfferJob::Error importing offer from marketplace.',
            [
                'error' => $exception->getMessage(),
            ]
        );
    }

    public function tags(): array
    {
        return ['ImportTask::' . $this->importTaskOffer->import_task_id, 'ImportTaskPage::' . $this->importTaskOffer->import_task_page_id, 'ImportOfferJob::' . $this->importTaskOffer->reference];
    }
}
