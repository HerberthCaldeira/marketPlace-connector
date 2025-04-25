<?php

declare(strict_types = 1);

namespace App\Infrastructure\Hub;

use App\Domains\Hub\Contracts\IHubClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HubClient implements IHubClient
{
    public PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::baseUrl(config('services.hub.url'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])
            ->asJson();
    }

    public function sendOffer(array $data): array
    {
        try {
            $response = $this->api->post('/hub/create-offer', $data);
            logger('Send Offer To Hub data before parse::', ['response' => $response->body()]);
            $response->throw();

            return $response->json();
        } catch (\Exception $e) {
            Log::error(
                'HubClient::Error sending offer to hub',
                [
                    'error' => $e->getMessage(),
                ]
            );

            throw $e;
        }
    }
}
