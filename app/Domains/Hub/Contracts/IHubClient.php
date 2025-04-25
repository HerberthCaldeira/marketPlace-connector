<?php

declare(strict_types = 1);

namespace App\Domains\Hub\Contracts;

interface IHubClient
{
    public function sendOffer(array $data): array;
}
