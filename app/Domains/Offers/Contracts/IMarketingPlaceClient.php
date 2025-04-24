<?php 

namespace App\Domains\Offers\Contracts;

interface IMarketingPlaceClient {
    public function getOffers(): array;
}