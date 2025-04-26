<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Hub\Jobs\SendOfferToHubJob;
use App\Domains\Offers\Services\ImportOffersService;
use App\Events\FetchOffersDetailsEvent;
use App\Models\ImportTask;
use App\Models\ImportTaskOffer;
use App\Models\ImportTaskPage;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * It's responsible for discover how many offers should be imported from a page and dispatch the event to fetch them.
 * 
 * @param ImportTask $importTask
 * @param ImportTaskPage $importTaskPage
 * 
 * @see FetchOffersDetailsListener
 */
class FetchPageOffersJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(
        public ImportTask $importTask,
        public ImportTaskPage $importTaskPage
    ) {
        //
    }

    /**
     * Execute the job.
     * 
     * @param ImportOffersService $importOffersService
     * @see FetchOffersDetailsListener
     */
    public function handle(ImportOffersService $importOffersService): void
    {
        logger('FetchPageOffersJob::Discover offers from a page', ['page_number' => $this->importTaskPage->page_number]);

        $pageOffers = $importOffersService->getPage($this->importTaskPage->page_number);

        $offers = collect($pageOffers['data']['offers']);

        foreach ($offers as $offer) {
            ImportTaskOffer::create([
                'import_task_id'      => $this->importTask->id,
                'import_task_page_id' => $this->importTaskPage->id,
                'reference'           => $offer,
                'status'              => 'pending',
            ]);          
        }
        /**
         * @see FetchOffersDetailsListener
         */
        event(new FetchOffersDetailsEvent($this->importTaskPage));     
    }

    public function failed($exception): void
    {
        $this->importTaskPage->update(['status' => 'failed']);
        Log::error(
            'FetchPageOffersJob::Error importing offers from marketplace.',
            [
                'error' => $exception->getMessage(),
            ]
        );
    }

    public function tags(): array
    {
        return ['ImportTask::' . $this->importTask->id, 'FetchPageOffersJob::' . $this->importTaskPage->page_number];
    }
}
