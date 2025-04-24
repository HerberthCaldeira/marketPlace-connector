<?php

namespace App\Infrastructure\Marketplace;

use App\Domains\Offers\Contracts\IMarketingPlaceClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

final class MarketPlaceClient implements IMarketingPlaceClient
{
    public PendingRequest $api;

    public function __construct()
    {
      
    }
    
    public function getOffers(): array
    {
      
    }
}
