<?php

declare(strict_types = 1);

namespace App\Domains\Task\UseCases;

use App\Domains\SharedKernel\Contracts\IUseCase;
use App\Domains\SharedKernel\Events\Dispatcher\IEventDispatcher;
use App\Domains\Task\Entities\Events\FetchedPagesEvent;
use App\Domains\Task\Entities\Gateways\IMarketingPlaceClient;
use App\Domains\Task\Entities\Repositories\ITaskPageRepository;

class FetchPagesUseCase implements IUseCase
{
    public function __construct(
        private readonly IMarketingPlaceClient $marketingPlaceClient,
        private readonly ITaskPageRepository $taskPageRepository,
        private readonly IEventDispatcher $eventDispatcher
    ) {
    }

    public function execute(int $taskId, int $page = 1): void
    {
        $pageOffers = $this->marketingPlaceClient->getPage($page);
        $pagination = $pageOffers['pagination'];
        $totalPages = $pagination['total_pages'];

        for ($currentPage = $page; $currentPage <= $totalPages; $currentPage++) {
            $this->taskPageRepository->create([
                'task_id'     => $taskId,
                'page_number' => $currentPage,
                'status'      => 'pending',
            ]);
        }

        $this->eventDispatcher->dispatch(new FetchedPagesEvent($taskId));
    }
}
