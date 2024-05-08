<?php

namespace App\Filament\Resources;
use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Exports\PostsExport;
use Filament\Resources\Resource;
use Illuminate\Validation\Rules\Can;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\Pages\ViewPost;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use Webbingbrasil\FilamentCopyActions\Tables\Actions\CopyAction;
use Webbingbrasil\FilamentCopyActions\Tables\CopyableTextColumn;
use Filament\Tables\Columns\TextColumn;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left';
    protected static ?string $navigationGroup= 'Posts Managment';
    protected static ?int $navigationSort= 1;

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return  $record->description;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
           'description'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Post'=> $record->description,
            'Post_Creator'=> $record->user->name
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return ViewPost::getUrl(['record' => $record]);
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
                    ->required()
                    ->default(auth()->user()->id)
                    ->readOnly(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(5)
                    ->cols(10)
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CopyableTextColumn::make('description')
                    ->label('Post')
                    ->limit(35)
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Post Creator')
                    ->sortable(),
                TextColumn::make('comments_count')
                    ->counts('comments')
                    ->label('Total Comments'),
                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // ...
            ])
            ->actions([
                CopyAction::make()
                    ->color('success')
                    ->copyable(fn ($record) =>
                        [$record->description,
                        $record->user->name,
                        $record->created_at,
                        $record->updated_at,
                        ]),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    //->visible(fn (Post $record): bool => auth()->user()->can('delete', $record)),
                    ,
                    BulkAction::make('export')
                    ->label('Export to excel')
                    ->deselectRecordsAfterCompletion()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Collection $records){
                        return Excel ::download(new PostsExport($records),'comments.xlsx');
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
