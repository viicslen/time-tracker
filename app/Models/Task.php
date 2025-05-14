<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskStatus;
use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $attributes = [
        'status' => TaskStatus::Pending,
    ];

    protected $dispatchesEvents = [
        'created' => TaskCreated::class,
        'updated' => TaskUpdated::class,
    ];

    protected function casts(): array
    {
        return [
            'project_id' => 'integer',
            'status' => TaskStatus::class,
            'discord_webhook_enabled' => 'boolean',
        ];
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User,Task>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Project,Task>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return HasMany<TaskUpdate,Task>
     */
    public function taskUpdates(): HasMany
    {
        return $this->hasMany(TaskUpdate::class);
    }
}
