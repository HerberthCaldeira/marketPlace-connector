<?php

declare(strict_types=1);

namespace App\Domains\Hub\UseCases;

use App\Domains\Hub\Services\HubService;
use App\Domains\Offers\States\SentToHubState;
use App\Models\ImportTaskOffer;

class SendOfferToHub
{
    public function __construct(
        private readonly HubService $hubService
    ) {
    }

    public function execute(ImportTaskOffer $importTaskOffer): void
    {
        try {
            $this->hubService->sendOffer($importTaskOffer->payload);
            $importTaskOffer->update(['status' => 'completed']);
            $importTaskOffer->setState(new SentToHubState($importTaskOffer));
        } catch (\Throwable $exception) {
            $importTaskOffer->update(['status' => 'failed']);
            throw $exception;
        }
    }
}
