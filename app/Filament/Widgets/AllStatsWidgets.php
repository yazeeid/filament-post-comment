<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AllStatsWidgets extends BaseWidget
{
    protected static ?int $sort = 1;


    protected function getStats(): array
    {
        return [
            stat::make('Users',User::query()->count())
                ->description('all users from database')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            stat::make('Posts',Post::count())
                ->description('all posts from database')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7,2, 7, 3, 15, 7, 13]),

            stat::make('Comments',Comment::count())
                ->description('all comments from database')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([5,1, 17, 4, 13, 10, 13]),
        ];
    }
}
