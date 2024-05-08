<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CommentChartWidgets extends ChartWidget
{
    protected static ?int $sort = 3;


    protected static ?string $heading = 'Comments chart';
    protected static string $color = 'success';
    protected static ?string $pollingInterval = '10s';
    protected static ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $data = Trend::model(Comment::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Comments',
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
