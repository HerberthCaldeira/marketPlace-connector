<?php

declare(strict_types=1);

namespace App\Domains\Task\UseCases;

use App\Domains\SharedKernel\Events\Dispatcher\IEventDispatcher;
use App\Domains\Task\Entities\Events\FetchedOffersReferencesFromPageEvent;
use App\Domains\Task\Entities\Gateways\IMarketingPlaceClient;
use App\Domains\Task\Entities\Repositories\ITaskOfferRepository;
use App\Domains\Task\Entities\Repositories\ITaskPageRepository;

class FetchOffersReferencesOfaPageUseCase
{
    public function __construct(
        private IMarketingPlaceClient $marketingPlaceClient,
        private ITaskPageRepository $taskPageRepository,
        private ITaskOfferRepository $taskOfferRepository,
        private IEventDispatcher $eventDispatcher
    ) {
    }

    public function execute(int $pageId): void
    {
        $pageEntity = $this->taskPageRepository->getById($pageId);

        $pageOffers = $this->marketingPlaceClient->getPage($pageEntity->pageNumber);
        $offers = collect($pageOffers['data']['offers']);

        foreach ($offers as $offer) {
            $this->taskOfferRepository->create([
                'task_id' => $pageEntity->taskId,
                'task_page_id' => $pageEntity->id,
                'reference' => $offer,
                'status' => 'pending',
            ]);
      
        }

        $this->taskPageRepository->update($pageId, ['status' => 'completed']);


        $this->eventDispatcher->dispatch(new FetchedOffersReferencesFromPageEvent($pageEntity->id));

    }
}
