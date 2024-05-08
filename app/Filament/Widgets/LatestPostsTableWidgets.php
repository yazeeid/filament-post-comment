<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Webbingbrasil\FilamentCopyActions\Tables\CopyableTextColumn;


class LatestPostsTableWidgets extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';


    public function table(Table $table): Table
    {
        return $table
            ->query(Post::query())
            ->defaultSort('created_at','desc')
            ->columns([
                TextColumn::make('description')
                    ->label('Post')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Post Creator')
                    ->sortable(),
                TextColumn::make('comments_count')
                    ->label('Total Comments')
                    ->counts('comments'),
                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
