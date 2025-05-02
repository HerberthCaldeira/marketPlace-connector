<?php

declare(strict_types=1);

namespace App\Domains\Offers\UseCases;

use App\Domains\Offers\Services\OffersService;
use App\Events\FetchOffersDetailsEvent;
use App\Models\ImportTaskOffer;
use App\Models\ImportTaskPage;

class FetchPageOffers
{
    public function __construct(
        private readonly OffersService $offersService
    ) {
    }

    public function execute(ImportTaskPage $importTaskPage): void
    {
        $pageOffers = $this->offersService->getPage($importTaskPage->page_number);
        $offers = collect($pageOffers['data']['offers']);

        foreach ($offers as $offer) {
            ImportTaskOffer::create([
                'import_task_id' => $importTaskPage->import_task_id,
                'import_task_page_id' => $importTaskPage->id,
                'reference' => $offer,
                'status' => 'pending',
            ]);
        }

        /**
         * @see App\Listeners\FetchOffersDetailsListener
         */
        event(new FetchOffersDetailsEvent($importTaskPage));
    }
}
