<?php

declare(strict_types=1);

namespace App\Domains\Task\UseCases;

use App\Domains\Task\Entities\Gateways\IHubClient;
use App\Domains\Task\Entities\Repositories\ITaskOfferRepository;

class SendOfferToHubUseCase
{
    public function __construct(
        private IHubClient $hubClient,
        private ITaskOfferRepository $taskOfferRepository
    ) {
    }

    public function execute(int $offerId): void
    {
        try {
            $offerEntity = $this->taskOfferRepository->getById($offerId);

            $this->hubClient->sendOffer($offerEntity->payload);

            $this->taskOfferRepository->update($offerId, ['status' => 'completed']);
        } catch (\Throwable $exception) {
            $this->taskOfferRepository->update($offerId, ['status' => 'failed']);
            throw $exception;
        }
    }
}
