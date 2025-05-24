<?php

declare(strict_types = 1);

namespace App\Domains\Task\UseCases;

use App\Domains\SharedKernel\Contracts\IUseCase;
use App\Domains\SharedKernel\Events\Dispatcher\IEventDispatcher;
use App\Domains\Task\Entities\Events\FetchedPagesEvent;
use App\Domains\Task\Entities\Gateways\IMarketingPlaceClient;
use App\Domains\Task\Entities\PageEntity;
use App\Domains\Task\Entities\Repositories\ITaskPageRepository;
use App\Domains\Task\Entities\Repositories\ITaskRepository;

class FetchPagesUseCase implements IUseCase
{
    public function __construct(
        private readonly IMarketingPlaceClient $marketingPlaceClient,
        private readonly ITaskRepository $taskRepository,
        private readonly ITaskPageRepository $taskPageRepository,
        private readonly IEventDispatcher $eventDispatcher
    ) {
    }

    public function execute(int $taskId, int $page = 1): void
    {
        $pageOffers = $this->marketingPlaceClient->getPage($page);
        $pagination = $pageOffers['pagination'];
        $totalPages = $pagination['total_pages'];

        $taskEntity = $this->taskRepository->getById($taskId);

        for ($currentPage = $page; $currentPage <= $totalPages; $currentPage++) {
            $this->taskPageRepository->create(PageEntity::create($taskEntity, $currentPage));
        }
        
        /**
         * @see App\Domains\Task\Infra\Listeners\OnFetchedPages
         */
        $this->eventDispatcher->dispatch(new FetchedPagesEvent($taskId));
    }
}
