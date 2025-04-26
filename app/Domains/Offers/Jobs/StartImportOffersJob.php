<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Offers\Services\ImportOffersService;
use App\Events\FetchPagesOffersEvent;
use App\Models\ImportTask;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * It's responsible for discover how many pages of offers should be imported and dispatch the event to fetch them.
 * 
 * @param ImportTask $importTask
 * @param int $page
 * @see App\Listeners\FetchPagesOffersListener
 * 
 */
class StartImportOffersJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(public ImportTask $importTask, public ?int $page = 1)
    {
    }

    /**
     * Execute the job.
     * 
     * @param ImportOffersService $importOffersService
     * @see App\Listeners\FetchPagesOffersListener
     */
    public function handle(ImportOffersService $importOffersService): void
    {
        $pageOffers = $importOffersService->getPage($this->page);
        $pagination = $pageOffers['pagination'];
        $totalPages = $pagination['total_pages'];

        logger('StartImportOffersJob::Discover total pages', ['page' => $this->page, 'totalPages' => $totalPages]);

        for ($this->page; $this->page <= $totalPages; $this->page++) {
            $this->importTask->pages()->create([
                'import_task_id' => $this->importTask->id,
                'page_number'    => $this->page,
                'status'         => 'pending',
            ]);
        }

        /**
         * @see App\Listeners\FetchPagesOffersListener
         */
        event(new FetchPagesOffersEvent($this->importTask));
    }

    public function failed($exception): void
    {
        $this->importTask->update(['status' => 'failed', 'finished_at' => now()]);
        Log::error(
            'StartImportOffersJob::Error importing offers from marketplace.',
            [
                'importTaskId' => $this->importTask->id,
                'error'        => $exception->getMessage(),
            ]
        );
    }

    public function tags(): array
    {
        return ['StartImportOffersJob::importTask::' . $this->importTask->id];
    }
}
