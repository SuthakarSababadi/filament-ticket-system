<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
       //     Actions\DeleteAction::make(),

       Action::make('updatePassword')
    ->form([
        TextInput::make('password')
        ->password()
        ->required()
        ->confirmed(),

        TextInput::make('password_confirmation')
        ->password()
        ->required(),
    ])
    ->action(function (array $data)
    {
        $this->record->update([
            'password' => $data['password'],
        ]);
    })
    ];

    }

}
