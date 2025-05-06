<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\Gateways;

interface IMarketingPlaceClient
{
    public function getPage(int $page): array;

    public function getOffer(string $offerId): array;
}
