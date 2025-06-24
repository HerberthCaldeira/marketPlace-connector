<?php

declare(strict_types = 1);

namespace App\Domains\Task\UseCases;

use App\Domains\SharedKernel\Events\Dispatcher\IEventDispatcher;
use App\Domains\Task\Entities\Events\FetchedOfferDetailEvent;
use App\Domains\Task\Entities\Gateways\IMarketingPlaceClient;
use App\Domains\Task\Entities\Repositories\ITaskOfferRepository;
use App\Domains\SharedKernel\Contracts\IUseCase;

class FetchOfferDetailUseCase implements IUseCase
{
    public function __construct(
        private readonly IMarketingPlaceClient $marketingPlaceClient,
        private readonly ITaskOfferRepository $taskOfferRepository,
        private readonly IEventDispatcher $eventDispatcher
    ) {
    }

    public function execute(int $offerId): void
    {
        $offerEntity = $this->taskOfferRepository->getById($offerId);
        $data  = $this->marketingPlaceClient->getOffer($offerEntity->reference);

        $offerEntity->payload = $data['data'];
        $offerEntity->status = 'fetched';

        $this->taskOfferRepository->update($offerEntity);

        /**
         * @see App\Domains\Task\Infra\Listeners\OnFetchedOfferDetail
         */
        $this->eventDispatcher->dispatch(new FetchedOfferDetailEvent($offerId));
    }
}
