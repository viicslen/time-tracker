<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use Filament\Forms\Components\Textarea;
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
                    ->rows(10)
                    ->autosize()
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('description')
                    ->grow()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable()
                    ->dateTimeTooltip(),
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
