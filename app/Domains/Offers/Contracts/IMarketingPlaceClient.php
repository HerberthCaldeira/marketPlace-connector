<?php

declare(strict_types = 1);

namespace App\Domains\Offers\Contracts;

interface IMarketingPlaceClient
{
    public function getAllOffersFromAPage(int $page): array;

    public function getOffer(string $offerId): array;
}
