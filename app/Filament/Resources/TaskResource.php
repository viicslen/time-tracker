<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers\TaskUpdatesRelationManager;
use App\Enums\TaskStatus;
use App\Models\Task;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * @return array<array-key,array>
     */
    protected static function statuses(): array
    {
        return array_merge(...array_map(static fn(TaskStatus $case) => [$case->value => $case->name], TaskStatus::cases()));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(50)
                    ->columnSpan(2),

                Select::make('status')
                    ->required()
                    ->native(false)
                    ->options(self::statuses()),

                Select::make('project_id')
                    ->label('Project')
                    ->searchable(['name', 'description'])
                    ->relationship('project', 'name')
                    ->createOptionForm([
                        TextInput::make('name')->required(),
                        Textarea::make('description'),
                    ])
                    ->editOptionForm([
                        TextInput::make('name')->required(),
                        Textarea::make('description'),
                    ])
                    ->preload(),

                Textarea::make('description')
                    ->maxLength(255)
                    ->autosize()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(fn (TaskStatus $state): string => match ($state) {
                        TaskStatus::Pending => 'info',
                        TaskStatus::InProgress => 'warning',
                        TaskStatus::Completed => 'success',
                    }),

                TextColumn::make('name')
                    ->grow()
                    ->sortable()
                    ->searchable()
                    ->description(fn (Task $record): ?string => $record->description),

                TextColumn::make('project.name')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable()
                    ->dateTimeTooltip(),
            ])
            ->filters([
                SelectFilter::make('status')->options(self::statuses()),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
