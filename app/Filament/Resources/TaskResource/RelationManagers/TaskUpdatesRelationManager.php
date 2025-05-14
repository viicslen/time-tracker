<?php

declare(strict_types=1);

namespace App\Filament\Resources\TaskResource\RelationManagers;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TaskUpdatesRelationManager extends RelationManager
{
    protected static string $relationship = 'taskUpdates';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('description')
                    ->maxLength(1024)
                    ->columnSpanFull()
                    ->rows(10)
                    ->autosize()
                    ->required(),

                MarkdownEditor::make('context')
                    ->columnSpanFull(),

                Toggle::make('discord_webhook_enabled')
                    ->label('Discord Webhook')
                    ->onIcon('heroicon-m-bell')
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(true)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('description')
                    ->searchable()
                    ->wrap()
                    ->grow(),

                TextColumn::make('created_at')
                    ->dateTimeTooltip()
                    ->toggleable()
                    ->sortable()
                    ->since(),
            ])
            ->headerActions([
                CreateAction::make(),
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
}
