<?php

namespace App\Filament\Resources;
use Filament\Forms;
use Filament\Tables;
use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Exports\CommentsExport;
use Filament\Resources\Resource;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Columns\Summarizers\Count;
use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\Pages\ViewComment;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CommentResource\RelationManagers;
use Webbingbrasil\FilamentCopyActions\Tables\Actions\CopyAction;
use Webbingbrasil\FilamentCopyActions\Tables\CopyableTextColumn;

class CommentResource extends Resource
{

    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup= 'Comments Managment';

    protected static ?int $navigationSort= 2;

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->comment;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
           'comment'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Comment '=> $record->comment,
            'Comment_Creator '=> $record->user->name
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return ViewComment::getUrl(['record' => $record]);
    }

    public static function getNavigationBadge(): ?string
    {

        return static::getModel()::count();
    }


    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->label('Comment Creator')
                    ->default(auth()->user()->id)
                    ->readOnly(),
                Forms\Components\Select::make('post_id')
                        ->relationship(name: 'post',titleAttribute: 'description')
                        ->required(),
                Forms\Components\Textarea::make('comment')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

        public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('post.description')
                    ->label('Post')
                    ->limit(35)
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(35)
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Comment Creator')
                    ->sortable(),
                    TextColumn::make('created_at')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                CopyAction::make()
                    ->color('success')
                    ->copyable(fn ($record) =>
                        [$record->post->description,
                        $record->comment,
                        $record->user->name,
                        $record->post->created_at,
                        $record->post->updated_at,
                        ]),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    //->hidden(),
                    ,
                    BulkAction::make('export')
                    ->label('Export to excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records){
                        return Excel ::download(new CommentsExport($records),'comments.xlsx');
                    })

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'view' => Pages\ViewComment::route('/{record}'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
