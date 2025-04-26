<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
/**
 * Implements the import task page model.
 * @property int $id
 * @property int $import_task_id
 * @property int $page_number
 * @property string $status
 * @property \DateTime $started_at
 * @property \DateTime $finished_at
 * @property \DateTime $failed_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class ImportTaskPage extends Model
{
    protected $fillable = [
        'import_task_id',
        'page_number',
        'status',
        'started_at',
        'finished_at',
        'failed_at',
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
        'failed_at'   => 'datetime',
    ];

    public function importTask(): BelongsTo
    {
        return $this->belongsTo(ImportTask::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(ImportTaskOffer::class);
    }
}
