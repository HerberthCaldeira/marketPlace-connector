<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\Gateways;

interface IHubClient
{
    public function sendOffer(array $data): array;
}
