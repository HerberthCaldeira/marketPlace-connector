<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Offers\Services\ImportOffersService;
use App\Models\ImportTask;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

/**
 * It's responsible for importing offers from the marketplace.
 */
class StartImportOffersJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(public ImportTask $importTask, public ?int $page = 1)
    {
    }

    public function handle(ImportOffersService $importOffersService): void
    {
        $pageOffers = $importOffersService->getPage($this->page);
        $pagination = $pageOffers['pagination'];
        $totalPages = $pagination['total_pages'];

        logger('StartImportOffersJob::handle', ['page' => $this->page, 'totalPages' => $totalPages]);

        $batchJobs = [];

        for ($this->page; $this->page <= $totalPages; $this->page++) {
            $importTaskPage = $this->importTask->pages()->create([
                'import_task_id' => $this->importTask->id,
                'page_number'    => $this->page,
                'status'         => 'pending',
            ]);

            $batchJobs[] = new ImportPageOffersJob($this->importTask, $importTaskPage, $this->page);
        }

        $importTask = $this->importTask;

        Bus::batch($batchJobs)
            ->then(function () use ($importTask): void {
                $importTask->update(['status' => 'completed']);
                logger('StartImportOffersJob::Batch success.', ['importTaskId' => $importTask->id]);
            })->catch(function () use ($importTask): void {
                $importTask->update(['status' => 'failed']);
                logger('StartImportOffersJob::Batch failed.', ['importTaskId' => $importTask->id]);
            })->finally(function () use ($importTask): void {
                $importTask->update(['finished_at' => now()]);
                logger('StartImportOffersJob::Batch finished.', ['importTaskId' => $importTask->id]);
            })->dispatch();
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
