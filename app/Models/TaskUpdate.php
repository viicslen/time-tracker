<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskUpdate extends Model
{
    protected $fillable = [
        'task_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'task_id' => 'integer',
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
