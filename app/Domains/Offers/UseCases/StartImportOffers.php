<?php

declare(strict_types=1);

namespace App\Domains\Offers\UseCases;

use App\Domains\Offers\Services\OffersService;
use App\Events\FetchPagesEvent;
use App\Models\ImportTask;

class StartImportOffers
{
    public function __construct(
        private readonly OffersService $offersService
    ) {
    }

    public function execute(ImportTask $importTask, int $page = 1): void
    {
        $pageOffers = $this->offersService->getPage($page);
        $pagination = $pageOffers['pagination'];
        $totalPages = $pagination['total_pages'];

        for ($currentPage = $page; $currentPage <= $totalPages; $currentPage++) {
            $importTask->pages()->create([
                'import_task_id' => $importTask->id,
                'page_number' => $currentPage,
                'status' => 'pending',
            ]);
        }

        /**
         * @see App\Listeners\FetchPagesListener
         */
        event(new FetchPagesEvent($importTask));
    }
}
