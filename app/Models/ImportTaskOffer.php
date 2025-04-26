<?php

declare(strict_types = 1);

namespace App\Models;

use App\Domains\Offers\States\OfferState;
use App\Domains\Offers\States\PendingState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Implements the import task offer model.
 * @property int $id
 * @property int $import_task_id
 * @property int $import_task_page_id
 * @property string $reference
 * @property string $status
 * @property array $payload
 * @property \DateTime $started_at
 * @property \DateTime $finished_at
 * @property \DateTime $failed_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at 
 * 
 */
class ImportTaskOffer extends Model
{
    protected $fillable = [
        'import_task_id',
        'import_task_page_id',
        'reference',
        'status',
        'payload',
        'started_at',
        'finished_at',
        'failed_at',
    ];

    protected $casts = [
        'payload'     => 'array',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
        'failed_at'   => 'datetime',
    ];

    private ?OfferState $state = null;

    public function page(): BelongsTo
    {
        return $this->belongsTo(ImportTaskPage::class, 'import_task_page_id');
    }

    public function getState(): OfferState
    {
        if (!$this->state instanceof \App\Domains\Offers\States\OfferState) {
            $this->state = new PendingState($this);
        }

        return $this->state;
    }

    public function setState(OfferState $state): void
    {
        $this->state = $state;
    }

    public function sendToHub(): void
    {
        $this->getState()->sendToHub();
    }

    public function fail(string $error): void
    {
        $this->getState()->fail($error);
    }
}
