<?php

declare(strict_types = 1);

namespace App\Domains\Hub\Services;

use App\Domains\Hub\Contracts\IHubClient;

class HubService
{
    public function __construct(
        /**
         * @var IHubClient
         */
        private readonly IHubClient $hubClient
    ) {
    }

    /**
     * It's responsible for sending an offer to the hub.
     * 
     * @param array $data
     * 
     */
    public function sendOffer(array $data): array
    {
        return $this->hubClient->sendOffer($data);
    }
}
