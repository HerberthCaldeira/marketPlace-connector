<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Jobs;

use App\Domains\Task\UseCases\FetchPagesUseCase;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchPagesJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(
        private readonly int $taskId,
        private readonly ?int $page = 1
    ) {
    }

    public function handle(FetchPagesUseCase $useCase): void
    {
        $useCase->execute($this->taskId, $this->page);
    }

    public function failed(): void
    {
        logger('FetchPagesJob::failed', ['taskId' => $this->taskId]);
    }
}
