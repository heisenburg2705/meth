<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahunAjaranResource\Pages;
use App\Filament\Resources\TahunAjaranResource\RelationManagers;
use App\Models\TahunAjaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;

class TahunAjaranResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ? string $slug = 'tahun-ajaran';
    protected static ? string $pluralLabel = 'tahun ajaran';
    protected static ?string $navigationGroup = 'Setup Master';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tahun_ajaran')
                    ->numeric()
                    ->minValue(2020)
                    ->rule(function($get) {
                        return Rule::unique('tahun_ajaran', 'tahun_ajaran')
                            ->where(fn($query) => $query->where('semester', $get('semester')));
                    }),
                Forms\Components\Select::make('semester')
                    ->options([
                        '1' => 'Ganjil',
                        '2' => 'Genap',
                    ])
                    ->required()
                    ->label('Semester'),
                Forms\Components\DatePicker::make('periode_mulai')
                    ->required()
                    ->label('Periode Mulai'),
                Forms\Components\DatePicker::make('periode_selesai')
                    ->required()
                    ->label('Periode Selesai'),
                Forms\Components\Select::make('status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Non-Aktif',
                    ])
                    ->required()
                    ->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahun_ajaran')->sortable()->searchable()->label('Tahun Ajaran'),
                TextColumn::make('semester')->sortable()->searchable()->label('Semester')
                    ->formatStateUsing(function ($state) {
                        return $state == '1' ? 'Ganjil' : 'Genap';
                    }),
                TextColumn::make('periode_mulai')->sortable()->label('Periode Mulai'),
                TextColumn::make('periode_selesai')->sortable()->label('Periode Selesai'),
                ToggleColumn::make('status')
                    ->label('Aktif')
                    ->sortable()
                    ->onColor('success')
                    ->offColor('danger')
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) {
                            TahunAjaran::where('id', '!=', $record->id)->update(['status' => 0]);
                        }
                    }),
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
                    ->action(function($record) {
                        if ($record->status == '1') {
                            Notification::make()
                                ->title('Tahun ajaran yang aktif tidak dapat dihapus.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $record->delete();
                        Notification::make()
                            ->title('Tahun ajaran berhasil dihapus.')
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
                                    ->title('Tahun ajaran yang aktif tidak dapat dihapus.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            foreach ($records as $record) {
                                $record->delete();
                            }

                            Notification::make()
                                ->title('Tahun ajaran berhasil dihapus.')
                                ->success()
                                ->send();
                        }),
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
            'index' => Pages\ListTahunAjarans::route('/'),
            'create' => Pages\CreateTahunAjaran::route('/create'),
            'edit' => Pages\EditTahunAjaran::route('/{record}/edit'),
        ];
    }
}
