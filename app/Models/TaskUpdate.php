<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\TaskUpdateCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskUpdate extends Model
{
    protected $fillable = [
        'task_id',
        'description',
    ];

    protected $dispatchesEvents = [
        'created' => TaskUpdateCreated::class,
    ];

    protected function casts(): array
    {
        return [
            'task_id' => 'integer',
            'discord_webhook_enabled' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Task,TaskUpdate>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
