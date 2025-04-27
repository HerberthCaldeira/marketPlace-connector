<?php

declare(strict_types=1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Offers\UseCases\StartImportOffers;
use App\Models\ImportTask;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class StartImportOffersJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(
        private readonly ImportTask $importTask,
        private readonly ?int $page = 1
    ) {
    }

    public function handle(StartImportOffers $useCase): void
    {
        try {
            $useCase->execute($this->importTask, $this->page);
        } catch (\Throwable $exception) {
            $this->importTask->update(['status' => 'failed', 'finished_at' => now()]);
            Log::error(
                'StartImportOffersJob::Error importing offers from marketplace.',
                [
                    'importTaskId' => $this->importTask->id,
                    'error' => $exception->getMessage(),
                ]
            );
            throw $exception;
        }
    }

    public function tags(): array
    {
        return ['StartImportOffersJob::importTask::' . $this->importTask->id];
    }
}
