<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Project extends Model
{
    /** @use HasFactory<Database\Factories\ProjectFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'owner_id' => 'integer',
        ];
    }

    /**
     * @return HasMany<Task,Project>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
