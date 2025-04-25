<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function page(): BelongsTo
    {
        return $this->belongsTo(ImportTaskPage::class, 'import_task_page_id');
    }
}
