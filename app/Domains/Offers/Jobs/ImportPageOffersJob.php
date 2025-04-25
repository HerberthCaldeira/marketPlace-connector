<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Hub\Jobs\SendOfferToHubJob;
use App\Domains\Offers\Services\ImportOffersService;
use App\Models\ImportTask;
use App\Models\ImportTaskOffer;
use App\Models\ImportTaskPage;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class ImportPageOffersJob implements ShouldQueue
{
    use Queueable, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ImportTask $importTask,
        public ImportTaskPage $importTaskPage
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ImportOffersService $importOffersService): void
    {
        logger('ImportPageOffersJob::handle', ['page_number' => $this->importTaskPage->page_number]);

        $pageOffers = $importOffersService->getPage($this->importTaskPage->page_number);

        $offers     = collect($pageOffers['data']['offers']);

        $chainJobs = [];
        foreach ($offers as $offer) {
            $importTaskOffer = ImportTaskOffer::create([
                'import_task_id' => $this->importTask->id,
                'import_task_page_id' => $this->importTaskPage->id,
                'reference' => $offer,
                'status' => 'pending',
            ]);

            $chainJobs[] = [
                new ImportOfferJob($importTaskOffer),
                new SendOfferToHubJob($importTaskOffer)
            ];
        }

        if ($offers->isNotEmpty()) {
            $importTaskPage = $this->importTaskPage; 
        
            Bus::batch($chainJobs)       
                ->then(function () use ($importTaskPage): void {
                    $importTaskPage->update(['status' => 'completed']);
                    logger('ImportPageOffersJob::Batch success.', ['importTaskPageId' => $importTaskPage->id, 'page_number' => $importTaskPage->page_number]);
                })->catch(function () use ($importTaskPage): void {
                    $importTaskPage->update(['status' => 'failed']);
                    logger('ImportPageOffersJob::Batch failed.', ['importTaskPageId' => $importTaskPage->id, 'page_number' => $importTaskPage->page_number]);
                })->finally(function () use ($importTaskPage): void {
                    $importTaskPage->update(['finished_at' => now()]);
                    logger('ImportPageOffersJob::Batch finished.', ['importTaskPageId' => $importTaskPage->id, 'page_number' => $importTaskPage->page_number]);
                })->dispatch();
        }
          
    }

    public function failed($exception): void
    {
        $this->importTaskPage->update(['status' => 'failed']);
        Log::error(
            'ImportPageOffersJob::Error importing offers from marketplace.',
            [
                'error' => $exception->getMessage(),
            ]
        );
    }

    public function tags(): array
    {
        return ['ImportTask::' . $this->importTask->id, 'ImportPageOffersJob::' . $this->importTaskPage->page_number ];
    }
}
