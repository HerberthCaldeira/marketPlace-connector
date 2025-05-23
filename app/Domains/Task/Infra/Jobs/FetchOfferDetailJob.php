<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Jobs;

use App\Domains\Task\UseCases\FetchOfferDetailUseCase;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchOfferDetailJob implements ShouldQueue
{
    use Batchable;
    use Queueable;

    public function __construct(
        private int $offerId
    ) {
    }

    public function handle(FetchOfferDetailUseCase $useCase): void
    {
        $useCase->execute($this->offerId);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error(
            'FetchOfferDetailJob::Error importing offer from marketplace.',
            [
                'error' => $exception->getMessage(),
            ]
        );
    }
}
