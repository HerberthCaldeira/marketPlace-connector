<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Models;

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
class Offer extends Model
{
    protected $table = 'task_page_offers';
    protected $fillable = [
        'task_id',
        'task_page_id',
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

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'import_task_page_id');
    }
}
