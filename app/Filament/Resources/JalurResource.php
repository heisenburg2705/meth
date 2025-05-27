<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JalurResource\Pages;
use App\Filament\Resources\JalurResource\RelationManagers;
use App\Models\Jalur;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Notifications\Notification;

class JalurResource extends Resource
{
    protected static ?string $model = Jalur::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $slug = 'jalur';
    protected static ?string $pluralLabel = 'jalur';
    protected static ?string $navigationGroup = 'Setup Master';
    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Jalur')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Nama Jalur'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Non-Aktif',
                    ])
                    ->required()
                    ->placeholder('Pilih Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Jalur')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('status')
                    ->label('Aktif')
                    ->sortable()
                    ->onColor('success')
                    ->offColor('danger')
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) {
                            Jalur::where('id', '!=', $record->id)->update(['status' => 0]);
                        }
                    }),
                TextColumn::make('details_count')
                    ->label('Jumlah Detail')
                    ->counts('details') // Nama relasi
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function($record) {
                        if ($record->status == '1') {
                            Notification::make()
                                ->title('Jalur yang aktif tidak dapat dihapus.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $record->delete();
                        Notification::make()
                            ->title('Jalur berhasil dihapus.')
                            ->success()
                            ->send();
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function($records) {
                            $activeYears = $records->first(function($record) {
                                return $record->status == '1';
                            });

                            if ($activeYears) {
                                Notification::make()
                                    ->title('Jalur yang aktif tidak dapat dihapus.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            foreach ($records as $record) {
                                $record->delete();
                            }

                            Notification::make()
                                ->title('Jalur berhasil dihapus.')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            JalurDetailRelationManagerResource\RelationManagers\DetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJalurs::route('/'),
            'create' => Pages\CreateJalur::route('/create'),
            'edit' => Pages\EditJalur::route('/{record}/edit'),
        ];
    }
}
