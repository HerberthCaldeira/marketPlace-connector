<?php

declare(strict_types=1);

namespace App\Domains\Hub\Jobs;

use App\Domains\Hub\UseCases\SendOfferToHub;
use App\Models\ImportTaskOffer;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendOfferToHubJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(
        private readonly ImportTaskOffer $importTaskOffer
    ) {
    }

    public function handle(SendOfferToHub $useCase): void
    {
        try {
            $useCase->execute($this->importTaskOffer);
        } catch (\Throwable $exception) {
            Log::error('SendOfferToHubJob::Error sending offer to hub', [
                'data' => $this->importTaskOffer->payload,
                'error' => $exception->getMessage()
            ]);
            throw $exception;
        }
    }

    public function tags(): array
    {
        return [
            'ImportTask::' . $this->importTaskOffer->import_task_id,
            'ImportTaskPage::' . $this->importTaskOffer->import_task_page_id,
            'SendOfferToHubJob::' . $this->importTaskOffer->reference
        ];
    }
}
