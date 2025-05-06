<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Models;

use App\Domains\Marktplace\Infra\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Implements the import task model.
 * @property int $id
 * @property string $status
 * @property \DateTime $started_at
 * @property \DateTime $finished_at
 * @property \DateTime $failed_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Task extends Model
{
    protected $fillable = [
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

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
