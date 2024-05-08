<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webbingbrasil\FilamentCopyActions\Pages\Actions\CopyAction;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            CopyAction::make()->copyable(fn ($record) =>
                [$record->user->name,
                $record->description,
                ]),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
