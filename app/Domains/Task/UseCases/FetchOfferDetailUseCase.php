<?php

declare(strict_types=1);

namespace App\Domains\Task\UseCases;

use App\Domains\SharedKernel\Events\Dispatcher\IEventDispatcher;
use App\Domains\Task\Entities\Events\FetchedOfferDetailEvent;
use App\Domains\Task\Entities\Gateways\IMarketingPlaceClient;
use App\Domains\Task\Entities\Repositories\ITaskOfferRepository;

class FetchOfferDetailUseCase
{
    public function __construct(
        private IMarketingPlaceClient $marketingPlaceClient,
        private ITaskOfferRepository $taskOfferRepository,
        private IEventDispatcher $eventDispatcher
   
    ) {
    }

    public function execute(int $offerId): void
    {
        $offer = $this->taskOfferRepository->getById($offerId);
        $data = $this->marketingPlaceClient->getOffer($offer->reference);

        $this->taskOfferRepository->update($offerId, [
            'payload' => $data['data'],
            'status' => 'fetched'
        ]);

        $this->eventDispatcher->dispatch(new FetchedOfferDetailEvent($offerId));
    }
}
