<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TicketsOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Tickets Overview';

    public ?string $filter = 'week';

    protected static ?int $sort = 2;

    protected function getFilters(): ?array
{
    return [

        'week' => 'Last week',
        'month' => 'Last month',
        'year' => 'This year',
    ];
}

    protected function getData(): array
    {

        $start = null;
        $end = null;
        $perData = null;

        switch ($this->filter) {
            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $perData = 'perDay';
                break;
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $perData = 'perDay';
                break;
            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $perData = 'perWeek';
                break;
            default:
                break;
        }
        $data = Trend::model(Ticket::class)
        ->between(
            start: $start,
            end: $end,
        )
        ->$perData()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Tickets data',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
