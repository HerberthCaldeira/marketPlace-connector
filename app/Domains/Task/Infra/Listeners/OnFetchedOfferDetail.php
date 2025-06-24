<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Listeners;

use App\Domains\Task\Entities\Events\FetchedOfferDetailEvent;
use App\Domains\Task\UseCases\SendOfferToHubUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;

final class OnFetchedOfferDetail implements ShouldQueue
{
    public function __construct(private SendOfferToHubUseCase $useCase){}

    public function handle(FetchedOfferDetailEvent $event ): void
    {
        logger('OnFetchedOfferDetail::offer', ['id' => $event->offerId]);
        $this->useCase->execute($event->offerId);
    }
}
