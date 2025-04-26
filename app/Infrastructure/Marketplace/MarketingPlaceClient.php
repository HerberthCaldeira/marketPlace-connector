<?php

declare(strict_types = 1);

namespace App\Infrastructure\Marketplace;

use App\Domains\Offers\Contracts\IMarketingPlaceClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * It's responsible for making the requests to the marketplace.

 */
final class MarketingPlaceClient implements IMarketingPlaceClient
{
    /**
     * It's responsible for making the requests to the marketplace.
     *
     * @var PendingRequest
     */
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

    /**
     * It's responsible for getting a page of offers from the marketplace.
     *
     * @param int $page
     *
     */
    public function getPage(int $page): array
    {
        try {
            $response = $this->api->withQueryParameters([
                'page' => $page,
            ])->get('/offers');

            //logger('Get offers (page) from marketplace data before parse::', ['response' => $response->body()]);

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

    /**
     * It's responsible for getting an offer details from the marketplace.
     *
     * @param string $offerId
     *
     */
    public function getOffer(string $offerId): array
    {
        try {
            $response = $this->api->get("/offers/{$offerId}");
            //logger('Get offer from marketplace data before parse::', ['response' => $response->body()]);

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
