<?php

declare(strict_types = 1);

namespace App\Domains\Task\UseCases;

use App\Domains\Task\Entities\Gateways\IHubClient;
use App\Domains\Task\Entities\Repositories\ITaskOfferRepository;

class SendOfferToHubUseCase
{
    public function __construct(
        private readonly IHubClient $hubClient,
        private readonly ITaskOfferRepository $taskOfferRepository
    ) {
    }

    public function execute(int $offerId): void
    {
        try {
            $offerEntity = $this->taskOfferRepository->getById($offerId);

            $this->hubClient->sendOffer($offerEntity->payload);

            $offerEntity->status = 'completed';

            $this->taskOfferRepository->update($offerEntity);
        } catch (\Throwable $exception) {
            
            $offerEntity = $this->taskOfferRepository->getById($offerId);
            $offerEntity->status = 'failed';
            $this->taskOfferRepository->update($offerEntity);

            throw $exception;
        }
    }
}
