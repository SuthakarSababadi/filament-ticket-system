<?php

namespace App\Livewire;
use Filament\Tables;
use App\Models\Role;
use App\Models\User;
use App\Models\Ticket;
use Livewire\Component;
use Filament\Tables\Table;
use Tables\Actions\EditAction;

use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;

use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;


class ListTickets extends Component implements HasForms, HasTable
{


    use InteractsWithTable; 
    use InteractsWithForms;
    protected static ?string $model = Ticket::class;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Ticket::query())
            ->columns([
                TextColumn::make('title')
                ->description(fn (Ticket $record): ?string => $record?->description)
                ->searchable()
                ->sortable(),

                SelectColumn::make('status')
                ->options(self::$model::STATUS),

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
    
    public function render()
    {
        return view('livewire.list-tickets');
    }
}
