<?php

namespace App\Filament\Resources\CommentResource\Pages;

use Filament\Actions;
use App\Models\Comment;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CommentResource;
use Webbingbrasil\FilamentCopyActions\Tables\Actions\CopyAction;

class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

        ];
    }

    public function getTabs() : array
    {
        return [
            'All' => Tab::make()
            ->badge(Comment::query()->where('created_at','<=',now())->count()),
            'Last Week' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('created_at','>=',now()->subWeek());
                })
                ->badge(Comment::query()->where('created_at','>=',now()->subWeek())->count()),
                'Last Month' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('created_at','>=',now()->subMonth());
                })
                ->badge(Comment::query()->where('created_at','>=',now()->subMonth())->count()),
        ];
    }
}
