<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $attributes = [
        'status' => TaskStatus::Pending,
    ];

    protected function casts(): array
    {
        return [
            'project_id' => 'integer',
            'status' => TaskStatus::class,
        ];
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
