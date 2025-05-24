<?php

declare(strict_types = 1);

namespace App\Domains\Task\UseCases;

use App\Domains\SharedKernel\Events\Dispatcher\IEventDispatcher;
use App\Domains\Task\Entities\Events\FetchedOffersReferencesFromPageEvent;
use App\Domains\Task\Entities\Gateways\IMarketingPlaceClient;
use App\Domains\Task\Entities\OfferEntity;
use App\Domains\Task\Entities\Repositories\ITaskOfferRepository;
use App\Domains\Task\Entities\Repositories\ITaskPageRepository;
use Illuminate\Support\Facades\DB;

class FetchOffersReferencesOfaPageUseCase
{
    public function __construct(
        private readonly IMarketingPlaceClient $marketingPlaceClient,
        private readonly ITaskPageRepository $taskPageRepository,
        private readonly ITaskOfferRepository $taskOfferRepository,
        private readonly IEventDispatcher $eventDispatcher
    ) {
    }

    public function execute(int $pageId): void
    {
        DB::transaction(function () use ($pageId) {
            $pageEntity = $this->taskPageRepository->getById($pageId);

            $pageOffers = $this->marketingPlaceClient->getPage($pageEntity->pageNumber);
            $offers     = collect($pageOffers['data']['offers']);

            foreach ($offers as $offer) {
                $this->taskOfferRepository->create(OfferEntity::create(
                    $pageEntity->taskId,
                    $pageEntity->id,
                    $offer,
                    'pending',
                ));
            }

            $pageEntity->status = 'fetched';

            $this->taskPageRepository->update($pageEntity);

            /**
             * @see App\Domains\Task\Infra\Listeners\OnFetchedOffersReferencesFromPage
             */
            $this->eventDispatcher->dispatch(new FetchedOffersReferencesFromPageEvent($pageEntity->id));
        });
    }
}
