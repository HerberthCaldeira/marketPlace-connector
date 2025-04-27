<?php

declare(strict_types=1);

namespace App\Domains\Offers\UseCases;

use App\Domains\Offers\Services\OffersService;
use App\Domains\Offers\States\FetchedState;
use App\Models\ImportTaskOffer;

class FetchOfferDetail
{
    public function __construct(
        private readonly OffersService $offersService
    ) {
    }

    public function execute(ImportTaskOffer $importTaskOffer): void
    {
        $data = $this->offersService->getOffer($importTaskOffer->reference);
        
        $importTaskOffer->update([
            'payload' => $data['data'],
            'status' => 'fetched',
        ]);
        
        $importTaskOffer->setState(new FetchedState($importTaskOffer));
        $importTaskOffer->sendToHub();
    }
}
