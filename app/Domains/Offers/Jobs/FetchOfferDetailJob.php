<?php

declare(strict_types=1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Offers\UseCases\FetchOfferDetail;
use App\Models\ImportTaskOffer;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchOfferDetailJob implements ShouldQueue
{
    use Batchable;
    use Queueable;

    public function __construct(
        private readonly ImportTaskOffer $importTaskOffer
    ) {
    }

    public function handle(FetchOfferDetail $useCase): void
    {
        logger("FetchOfferDetailJob::handle::{$this->importTaskOffer->reference}");

        try {
            $useCase->execute($this->importTaskOffer);
        } catch (\Throwable $exception) {
            $this->importTaskOffer->fail($exception->getMessage());
            Log::error(
                'FetchOfferDetailJob::Error importing offer from marketplace.',
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
            'ImportTask::' . $this->importTaskOffer->import_task_id,
            'ImportTaskPage::' . $this->importTaskOffer->import_task_page_id,
            'ImportTaskOffer::' . $this->importTaskOffer->reference
        ];
    }
}
