<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Offers\Services\OffersService;
use App\Domains\Offers\States\FetchedState;
use App\Models\ImportTaskOffer;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * It's responsible for fetching the details of an offer from the marketplace and dispatching the event to send it to the hub.
 *
 * @see App\Listeners\SendOfferToHubListener
 *
 */
class FetchOfferDetailJob implements ShouldQueue
{
    use Batchable;
    use Queueable;

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
     * @param OffersService $offersService
     *
     */
    public function handle(OffersService $offersService): void
    {
        logger("FetchOfferDetailJob::handle::{$this->importTaskOffer->reference}");

        try {
            $data = $offersService->getOffer($this->importTaskOffer->reference);
            $this->importTaskOffer->update([
                'payload' => $data['data'],
                'status'  => 'fetched',
            ]);
            $this->importTaskOffer->setState(new FetchedState($this->importTaskOffer));
            $this->importTaskOffer->sendToHub();
        } catch (\Exception $e) {
            $this->failed($e);
        }
    }

    public function failed($exception): void
    {
        $this->importTaskOffer->fail($exception->getMessage());
        Log::error(
            'FetchOfferDetailJob::Error importing offer from marketplace.',
            [
                'error' => $exception->getMessage(),
            ]
        );
    }

    public function tags(): array
    {
        return ['ImportTask::' . $this->importTaskOffer->import_task_id, 'ImportTaskPage::' . $this->importTaskOffer->import_task_page_id, 'ImportTaskOffer::' . $this->importTaskOffer->reference];
    }
}
