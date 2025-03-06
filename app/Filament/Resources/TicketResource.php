<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use App\Models\Ticket;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use App\Filament\Resources\TicketResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Filament\Resources\TicketResource\RelationManagers\CategoriesRelationManager;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-s-ticket';

    public static function form(Form $form): Form
    {
        return $form
        ->columns(1)
            ->schema([
                TextInput::make('title')
                ->autofocus()
                ->required(),

                Textarea::make('description')
                ->rows(3),

                Select::make('status')
                ->options(self::$model::STATUS)
                ->required()
                ->in(Ticket::STATUS),

                Select::make('priority')
                ->options(self::$model::PRIORITY)
                ->required()
                ->in(Ticket::PRIORITY),

                Select::make('assigned_to')
                ->options(
                    User::whereHas('roles', function (Builder $query) {
                        $query->where('name', Role::ROLES['Agent']);
                    })->pluck('name', 'id' )->toArray()
                )
                ->required(),

                Textarea::make('comment')
                ->rows(3),

                FileUpload::make('attachment')   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing (fn (Builder $query) =>
                auth()->user()->hasRole(Role::ROLES['Admin']) ? $query : $query->where('assigned_to', auth()->user()->id)
            )
            ->columns([
                TextColumn::make('title')
                ->description(fn (Ticket $record): ?string => $record?->description)
                ->searchable()
                ->sortable(),

                SelectColumn::make('status')
                ->options(self::$model::STATUS),



                // TextColumn::make('status')
                // ->badge()
                // ->colors ([
                //     'success' => Ticket::STATUS['Closed'],
                //     'warning' => Ticket::STATUS['Archived'],
                //     'danger' => Ticket::STATUS['Open'],
                // ]),

                TextColumn::make('priority')->badge()
                ->colors ([
                    'success' => Ticket::PRIORITY['Low'],
                    'warning' => Ticket::PRIORITY['Medium'],
                    'danger' => Ticket::PRIORITY['High'],
                ]),

                TextColumn::make('assignedTo.name'),
                TextInputColumn::make('comment')

                ->searchable()
                ->sortable(),

                TextColumn::make('created_at')
                ->dateTime()
                ->sortable()

            ])
            ->filters([
                SelectFilter::make('status')
                ->options(self::$model::STATUS)
                ->placeholder('Filter by status'),

                SelectFilter::make('priority')
                ->options(self::$model::PRIORITY)
                ->placeholder('Filter by priority')

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CategoriesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
