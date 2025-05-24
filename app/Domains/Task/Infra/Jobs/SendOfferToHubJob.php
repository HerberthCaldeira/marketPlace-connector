<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Jobs;

use App\Domains\Task\UseCases\SendOfferToHubUseCase;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendOfferToHubJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(
        private int $offerId
    ) {
    }

    public function handle(SendOfferToHubUseCase $useCase): void
    {
        $useCase->execute($this->offerId);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SendOfferToHubJob::Error sending offer to hub', [
            'error' => $exception->getMessage(),
        ]);
    }
}
