<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),

                Toggle::make('discord_webhook_enabled')
                    ->label('Enable Discord Webhooks')
                    ->onIcon('heroicon-m-bell')
                    ->onColor('success')
                    ->default(false)
                    ->columnSpanFull(),

                TextInput::make('discord_webhook_url')
                    ->placeholder('https://discord.com/api/webhooks/123456789012345678/abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
                    ->label('Discord Webhook URL')
                    ->maxLength(2048)
                    ->columnSpanFull(),
            ]);
    }
}
