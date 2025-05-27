<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SyaratResource\Pages;
use App\Filament\Resources\SyaratResource\RelationManagers;
use App\Models\Syarat;
use Filament\Forms;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SyaratResource extends Resource
{
    protected static ?string $model = Syarat::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $slug = 'syarat';
    protected static ?string $pluralLabel = 'Syarat';
    protected static ?string $navigationGroup = 'Setup Master';
    protected static ?int $navigationSort = 7;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Syarat')
                    ->required()
                    ->unique(Syarat::class, 'nama', ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('kode')
                    ->label('Kode Syarat')
                    ->required()
                    ->unique(Syarat::class, 'kode', ignoreRecord: true)
                    ->maxLength(50),
                MultiSelect::make('tingkat')
                    ->label('Tingkat')
                    ->relationship('tingkat', 'singkatan')
                    ->preload()
                    ->required()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Syarat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode Syarat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tingkat.singkatan')
                    ->label('Tingkat')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
   Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSyarats::route('/'),
            'create' => Pages\CreateSyarat::route('/create'),
            'edit' => Pages\EditSyarat::route('/{record}/edit'),
        ];
    }
}
