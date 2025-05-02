<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Services;

use App\Domains\Offers\Contracts\IMarketingPlaceClient;

class OffersService
{
    public function __construct(
        /**
         * @var IMarketingPlaceClient
         *
         * @see App\Infrastructure\Marketplace\MarketingPlaceClient
         */
        private readonly IMarketingPlaceClient $marketingPlaceClient
    ) {
    }

    /**
     * Get offers from a page from the marketplace.
     *
     * @param int $page
     *
     * @return array
     */
    public function getPage(int $page): array
    {
        return $this->marketingPlaceClient->getPage($page);
    }

    /**
     * Get an offer details from the marketplace.
     *
     * @param string $offerId
     *
     * @return array
     */
    public function getOffer(string $offerId): array
    {
        return $this->marketingPlaceClient->getOffer($offerId);
    }
}
