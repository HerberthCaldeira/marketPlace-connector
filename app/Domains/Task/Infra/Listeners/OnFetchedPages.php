<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Listeners;

use App\Domains\Task\Entities\Events\FetchedPagesEvent;
use App\Domains\Task\Entities\Repositories\ITaskPageRepository;
use App\Domains\Task\Infra\Jobs\FetchOffersReferencesOfaPageJob;
use Illuminate\Support\Facades\Bus;

final readonly class OnFetchedPages
{
    public function __construct(
        private ITaskPageRepository $taskPageRepository
    ) {
    }

    public function handle(FetchedPagesEvent $event): void
    {
        logger('OnFetchedPages::page', ['id' => $event->taskId]);

        $pagesEntities = $this->taskPageRepository->getByTaskId($event->taskId, 'pending');

        $jobs = [];

        foreach ($pagesEntities as $pageEntity) {
            $jobs[] = new FetchOffersReferencesOfaPageJob($pageEntity->id);
        }

        Bus::batch($jobs)
            ->allowFailures()
            ->dispatch();
    }
}
