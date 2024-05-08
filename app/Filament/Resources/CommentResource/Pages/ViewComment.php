<?php

namespace App\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webbingbrasil\FilamentCopyActions\Pages\Actions\CopyAction;
class ViewComment extends ViewRecord
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            CopyAction::make()->copyable(fn ($record) =>
                [$record->user->name,
                $record->post->description,
                $record->comment,
                ]),
        ];
    }
}
