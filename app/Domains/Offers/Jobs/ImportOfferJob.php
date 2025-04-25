<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Hub\Jobs\SendOfferToHubJob;
use App\Domains\Offers\Services\ImportOffersService;
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
    public function __construct(public string $offerId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(ImportOffersService $importOffersService): void
    {
        logger("ImportOfferJob::handle::{$this->offerId}");
        $data = $importOffersService->getOffer($this->offerId);
        SendOfferToHubJob::dispatch($data['data'], $this->offerId);
    }

    public function failed($exception): void
    {
        Log::error(
            'ImportOfferJob::Error importing offer from marketplace.',
            [
                'error' => $exception->getMessage(),
            ]
        );
    }

    public function tags(): array
    {
        return ['batch::' . $this->batchId, 'offer::' . $this->offerId];
    }
}
