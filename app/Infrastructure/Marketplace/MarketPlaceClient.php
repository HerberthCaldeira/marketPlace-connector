<?php

declare(strict_types = 1);

namespace App\Infrastructure\Marketplace;

use App\Domains\Offers\Contracts\IMarketingPlaceClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class MarketPlaceClient implements IMarketingPlaceClient
{
    public PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::baseUrl(config('services.marketplace.url'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])
            ->asJson();
    }

    public function getAllOffersFromAPage(int $page): array
    {
        try {
            $response = $this->api->withQueryParameters([
                'page' => $page,
            ])->get('/offers');

            logger('Get offers (page) from marketplace data before parse::', ['response' => $response->body()]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error(
                'MarketPlaceClient::Error getting offers from marketplace.',
                [
                    'error' => $e->getMessage(),
                ]
            );

            throw $e;
        }
    }

    public function getOffer(string $offerId): array
    {
        try {
            $response = $this->api->get("/offers/{$offerId}");
            logger('Get offer from marketplace data before parse::', ['response' => $response->body()]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error(
                'MarketPlaceClient::Error getting offer from marketplace.',
                [
                    'error' => $e->getMessage(),
                ]
            );

            throw $e;
        }
    }
}
