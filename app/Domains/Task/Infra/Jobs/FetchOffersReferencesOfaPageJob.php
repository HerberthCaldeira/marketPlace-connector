<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Jobs;

use App\Domains\Task\UseCases\FetchOffersReferencesOfaPageUseCase;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchOffersReferencesOfaPageJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(
        private int $pageId
    ) {
    }

    public function handle(FetchOffersReferencesOfaPageUseCase $useCase): void
    {
        $useCase->execute($this->pageId);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error(
            'FetchPageOffersJob::Error importing offers from marketplace.',
            [
                    'error' => $exception->getMessage(),
                ]
            );
    }
}
