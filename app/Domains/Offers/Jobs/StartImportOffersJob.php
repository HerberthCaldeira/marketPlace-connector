<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Offers\Services\ImportOffersService;
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

    public function __construct(public ?int $page = 1)
    {
    }

    public function handle(ImportOffersService $importOffersService): void
    {
        logger('ImportOffersJob::handle', ['page' => $this->page]);

        $pageOffers = $importOffersService->getAllOffersFromAPage($this->page);

        $offers     = collect($pageOffers['data']['offers']);
        $pagination = $pageOffers['pagination'];

        if ($offers->isNotEmpty()) {
            Bus::batch($offers->map(fn ($offer): ImportOfferJob => new ImportOfferJob($offer)))->then(function (): void {
                logger('Batch success.');
            })->catch(function (): void {
                logger('Batch failed.');
            })->finally(function (): void {
                logger('Batch finished.');
            })->dispatch();
        }

        $nextPage = $this->page + 1;

        if ($pagination['total_pages'] > $this->page) {
            StartImportOffersJob::dispatch($nextPage);
        }

    }

    public function failed($exception): void
    {
        Log::error(
            'StartImportOffersJob::Error importing offers from marketplace.',
            [
                'error' => $exception->getMessage(),
            ]
        );
    }

    public function tags(): array
    {
        return ['StartImportOffersJob::page::' . $this->page];
    }
}
