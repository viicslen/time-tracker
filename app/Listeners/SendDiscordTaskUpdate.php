<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\TaskStatus;
use App\Events\TaskCreated;
use App\Events\TaskUpdateCreated;
use App\Events\TaskUpdated;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class SendDiscordTaskUpdate implements ShouldQueue
{
    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(TaskCreated|TaskUpdated|TaskUpdateCreated $event): bool
    {
        $task = $this->getTask($event);
        $url = $task->project?->discord_webhook_url ?? $task->user?->discord_webhook_url;
        $enabled = $task->project?->discord_webhook_enabled ?? $task->user?->discord_webhook_enabled ?? false;

        if ($event instanceof TaskUpdateCreated && $event->update->discord_webhook_enabled === false) {
            return false;
        }

        return $enabled && ! empty($url);
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCreated|TaskUpdated|TaskUpdateCreated $event): void
    {
        $task = $this->getTask($event);
        $url = $task->project?->discord_webhook_url ?? $task->user?->discord_webhook_url;

        if (empty($url)) {
            return;
        }

        $data = [
            "content" => null,
            "embeds" => [
                [
                    "title" => $task->name,
                    "description" => null,
                    "color" => match ($task->status) {
                        TaskStatus::Pending => 0x60a5fa,
                        TaskStatus::InProgress => 0xfbbf24,
                        TaskStatus::Completed => 0x4ade80,
                    },
                    "fields" => [
                        [
                            "name" => "Status",
                            "value" => $task->status->label(),
                            "inline" => false
                        ],
                    ],
                    "author" => [
                        "name" => "Time Tracker",
                    ],
                    "footer" => [
                        "text" => match (true) {
                            $event instanceof TaskCreated => "Task Created",
                            $event instanceof TaskUpdated => "Task Updated",
                            $event instanceof TaskUpdateCreated => "Task Update Added",
                        },
                    ]
                ]
            ],
            "username" => $task->user->name,
        ];

        if ($task->project) {
            $data['embeds'][0]['fields'][] = [
                "name" => "Project",
                "value" => $task->project->name,
                "inline" => false
            ];
        }

        if ($event instanceof TaskUpdateCreated) {
            $data['embeds'][0]['description'] = $event->update->description;
        } else {
            $data['embeds'][0]['fields'][] = [
                "name" => "Description",
                "value" => $task->description,
                "inline" => false
            ];
        }

        Http::post($url, $data);
    }

    protected function getTask(TaskCreated|TaskUpdated|TaskUpdateCreated $event): Task
    {
        return match (true) {
            $event instanceof TaskCreated => $event->task,
            $event instanceof TaskUpdated => $event->task,
            $event instanceof TaskUpdateCreated => $event->update->task,
        };
    }
}
