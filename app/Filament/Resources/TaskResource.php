<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages\CreateTask;
use App\Filament\Resources\TaskResource\Pages\EditTask;
use App\Filament\Resources\TaskResource\Pages\ListTasks;
use App\Filament\Resources\TaskResource\RelationManagers\TaskUpdatesRelationManager;
use App\Enums\TaskStatus;
use App\Models\Task;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    /**
     * @return array<array-key,array>
     */
    protected static function statuses(): array
    {
        return array_merge(...array_map(static fn(TaskStatus $case) => [
            $case->value => $case->label(),
        ], TaskStatus::cases()));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->maxLength(255)
                    ->columnSpan(2)
                    ->required(),

                Select::make('status')
                    ->options(self::statuses())
                    ->native(false)
                    ->required(),

                Select::make('project_id')
                    ->manageOptionForm([
                        TextInput::make('name')
                            ->maxLength(255)
                            ->required(),

                        Textarea::make('description')
                            ->maxLength(1024),

                        Toggle::make('discord_webhook_enabled')
                            ->label('Enable Discord Webhooks')
                            ->onIcon('heroicon-m-bell')
                            ->onColor('success')
                            ->default(false),

                        TextInput::make('discord_webhook_url')
                            ->placeholder('https://discord.com/api/webhooks/123456789012345678/abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
                            ->label('Discord Webhook URL')
                            ->maxLength(2048),
                    ])
                    ->relationship('project', 'name')
                    ->searchable(['name', 'description'])
                    ->label('Project')
                    ->preload(),

                Textarea::make('description')
                    ->maxLength(1024)
                    ->columnSpanFull()
                    ->autosize(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->formatStateUsing(fn (TaskStatus $state): string => __($state->label()))
                    ->color(fn (TaskStatus $state): string => match ($state) {
                        TaskStatus::Pending => 'info',
                        TaskStatus::InProgress => 'warning',
                        TaskStatus::Completed => 'success',
                    })
                    ->sortable()
                    ->badge(),

                TextColumn::make('name')
                    ->description(fn (Task $record): ?string => $record->description)
                    ->searchable()
                    ->sortable()
                    ->grow(),

                TextColumn::make('project.name')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTimeTooltip()
                    ->toggleable()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::statuses())
                    ->default([
                        TaskStatus::Pending->value,
                        TaskStatus::InProgress->value,
                    ])
                    ->multiple(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('status', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            TaskUpdatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var class-string<Task> $model */
        $model = static::getModel();

        return (string) $model::where('status', '!=', TaskStatus::Completed)->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of pending and in-progress tasks';
    }
}
