<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Services;

use App\Domains\Offers\Contracts\IMarketingPlaceClient;

class ImportOffersService
{
    public function __construct(
        /**
         * @var IMarketingPlaceClient
         */
        private readonly IMarketingPlaceClient $marketingPlaceClient
    ) {
    }

    public function getAllOffersFromAPage(int $page): array
    {
        return $this->marketingPlaceClient->getAllOffersFromAPage($page);
    }

    public function getOffer(string $offerId): array
    {
        return $this->marketingPlaceClient->getOffer($offerId);
    }
}
