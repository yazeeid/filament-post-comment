<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTabs(): array{

        return[
            'All' => Tab::make()
                ->badge(Post::query()->where('created_at','<=',now())->count())
                ->badgeColor('primary'),

            'Last Week' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('created_at','>=',now()->subWeek());
                })
                ->badge(Post::query()->where('created_at','>=',now()->subWeek())->count())
                ->badgeColor('primary'),
            'Last Month' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('created_at','>=',now()->subMonth());
                })
                ->badge(Post::query()->where('created_at','>=',now()->subMonth())->count())
                ->badgeColor('primary'),
        ];
    }

}
