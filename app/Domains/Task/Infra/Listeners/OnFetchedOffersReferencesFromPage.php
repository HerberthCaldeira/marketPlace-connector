<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Listeners;

use App\Domains\Task\Entities\Events\FetchedOffersReferencesFromPageEvent;
use App\Domains\Task\Entities\Repositories\ITaskOfferRepository;
use App\Domains\Task\Infra\Jobs\FetchOfferDetailJob;
use Illuminate\Support\Facades\Bus;

class OnFetchedOffersReferencesFromPage
{
    public function __construct(
        private readonly ITaskOfferRepository $taskOfferRepository
    ) {
    }

    public function handle(FetchedOffersReferencesFromPageEvent $event): void
    {
        $offersEntities = $this->taskOfferRepository->getByPageId($event->pageId, 'pending');

        $jobs = [];

        foreach ($offersEntities as $offerEntity) {
            $jobs[] = new FetchOfferDetailJob($offerEntity->id);
        }

        Bus::batch($jobs)
            ->allowFailures()
            ->dispatch();
    }
}
