<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TingkatResource\Pages;
use App\Filament\Resources\TingkatResource\RelationManagers;
use App\Models\Tingkat;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Exception;

class TingkatResource extends Resource
{
    protected static ?string $model = Tingkat::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $pluralLabel = 'tingkat';
    protected static ?string $slug = 'tingkat';
    protected static ?string $navigationGroup = 'Setup Master';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Tingkat')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Nama Tingkat'),
                TextInput::make('singkatan')
                    ->label('Singkatan')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Singkatan'),
                TextInput::make('kode_formulir')
                    ->label('Kode Formulir')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Kode Formulir'),
                TextInput::make('kode_tingkat')
                    ->label('Kode Tingkat')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Kode Tingkat'),
                TextInput::make('kuota')
                    ->numeric()
                    ->minValue(0)
                    ->label('Kuota')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Kuota'),
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->maxSize(2048)
                    ->directory('tingkat')
                    ->preserveFilenames()
                    ->enableOpen()
                    ->enableDownload()
                    ->placeholder('Upload Logo'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Tingkat')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('singkatan')
                    ->label('Singkatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kode_formulir')
                    ->label('Kode Formulir')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kode_tingkat')
                    ->label('Kode Tingkat')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kuota')
                    ->label('Kuota')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->getStateUsing(fn(Tingkat $record): string => $record->logo),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        try {
                            $record->delete();

                        } catch (Exception $e) {
                            // Send notification when exception occurs
                            Notification::make()
                                ->title('Creation Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            $gagal = collect();
                            $berhasil = 0;

                            foreach ($records as $record) {
                                try {
                                    $record->delete();
                                    $berhasil++;
                                } catch (\Throwable $e) {
                                    Notification::make()
                                        ->title('Gagal Menghapus')
                                        ->body("Gagal menghapus data: {$record->nama} - {$e->getMessage()}")
                                        ->danger()
                                        ->send();
                                }
                            }

                            if ($berhasil > 0) {
                                Notification::make()
                                    ->title('Penghapusan Berhasil')
                                    ->body("Berhasil menghapus {$berhasil} data.")
                                    ->success()
                                    ->send();
                            }

                            // if ($gagal->isNotEmpty()) {
                            //     Notification::make()
                            //         ->title('Sebagian Gagal Dihapus')
                            //         ->body(
                            //             collect($gagal)
                            //                 ->pluck('nama')
                            //                 ->map(fn($nama) => "- {$nama}")
                            //                 ->join("\n")
                            //         )
                            //         ->danger()
                            //         ->send();
                            // }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-trash'),
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
            'index' => Pages\ListTingkats::route('/'),
            'create' => Pages\CreateTingkat::route('/create'),
            'edit' => Pages\EditTingkat::route('/{record}/edit'),
        ];
    }
}
