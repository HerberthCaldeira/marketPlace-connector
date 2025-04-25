<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Jobs;

use App\Domains\Offers\Services\ImportOffersService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class ImportPageOffersJob implements ShouldQueue
{
    use Queueable, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $page)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ImportOffersService $importOffersService): void
    {
        logger('ImportPageOffersJob::handle', ['page' => $this->page]);

        $pageOffers = $importOffersService->getPage($this->page);

        $offers     = collect($pageOffers['data']['offers']);

        if ($offers->isNotEmpty()) {
            Bus::batch($offers->map(fn ($offer): ImportOfferJob => new ImportOfferJob($offer)))->then(function (): void {
                logger('Batch success.');
            })->catch(function (): void {
                logger('Batch failed.');
            })->finally(function (): void {
                logger('Batch finished.');
            })->dispatch();
        }    
    }

    public function failed($exception): void
    {
        Log::error(
            'ImportPageOffersJob::Error importing offers from marketplace.',
            [
                'error' => $exception->getMessage(),
            ]
        );
    }

    public function tags(): array
    {
        return ['batch::' . $this->batchId, 'ImportPageOffersJob::page::' . $this->page ];
    }
}
