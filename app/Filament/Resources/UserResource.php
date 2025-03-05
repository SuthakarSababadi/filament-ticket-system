<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = "Access Control";

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
                ->autofocus(),

                TextInput::make('email')
                ->required()
                ->email()
                ->unique(ignoreRecord:true),

                TextInput::make('password')
                ->password()
                ->required()
                ->minLength(6)
                ->hiddenOn('edit'),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable()
                ->sortable(),

                TextColumn::make('email')
                ->searchable(),

                TextColumn::make('roles.name')
                ->badge(),
            ])
            ->filters([
                SelectFilter::make('role')
                ->relationship('roles', 'name')
                ->preload(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('sendBulkSms')
                    ->modalButton('Send SMS')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->deselectRecordsAfterCompletion()
                    ->form([
                        Textarea::make('message')
                        ->placeholder('Type your message here...')
                        ->required()
                        ->rows(3),
                        Textarea::make('remarks'),

                    ])
                    ->action(function (array $data, Collection $collection) {
                        TextMessageService::sendMessage($data,$collection);

                    }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RolesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
