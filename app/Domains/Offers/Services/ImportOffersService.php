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

    public function getPage(int $page): array
    {
        return $this->marketingPlaceClient->getPage($page);
    }

    public function getOffer(string $offerId): array
    {
        return $this->marketingPlaceClient->getOffer($offerId);
    }
}
