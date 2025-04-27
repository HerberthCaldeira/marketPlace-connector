<?php

declare(strict_types=1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Offers\UseCases\FetchPageOffers;
use App\Models\ImportTaskPage;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchPageOffersJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(
        private readonly ImportTaskPage $importTaskPage
    ) {
    }

    public function handle(FetchPageOffers $useCase): void
    {
        logger('FetchPageOffersJob::Discover offers from a page', ['page_number' => $this->importTaskPage->page_number]);

        try {
            $useCase->execute($this->importTaskPage);
        } catch (\Throwable $exception) {
            $this->importTaskPage->update(['status' => 'failed']);
            Log::error(
                'FetchPageOffersJob::Error importing offers from marketplace.',
                [
                    'error' => $exception->getMessage(),
                ]
            );
            throw $exception;
        }
    }

    public function tags(): array
    {
        return [
            'ImportTask::' . $this->importTaskPage->import_task_id,
            'FetchPageOffersJob::' . $this->importTaskPage->page_number
        ];
    }
}
