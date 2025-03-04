<?php

namespace App\Filament\Widgets;

use App\Models\Role;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTickets extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                auth()->user()->hasRole(Role::ROLES['Admin']) ? Ticket::query() : Ticket::query()->where('assigned_to', auth()->user()->id)
            )
            ->columns([
               TextColumn::make('title'),
               TextColumn::make('status'),
               TextColumn::make('priority'),

            ]);
    }
}
