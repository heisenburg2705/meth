<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GelombangResource\Pages;
use App\Filament\Resources\GelombangResource\RelationManagers;
use App\Models\Gelombang;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MultiSelect;

class GelombangResource extends Resource
{
    protected static ?string $model = Gelombang::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Setup Master';
    protected static ?string $slug = 'gelombang';
    protected static ?string $pluralLabel = 'Gelombang';
    protected static ?int $navigationSort = 6;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tahun_ajaran_id')
                    ->label('Tahun Akademik')
                    ->relationship('tahunAjaran', 'tahun_ajaran')
                    ->required(),

                TextInput::make('nama')
                    ->label('Nama Gelombang')
                    ->required(),

                TextInput::make('kode')
                    ->label('Kode Gelombang')
                    ->required(),

                Select::make('tingkat_id')
                    ->label('Tingkat')
                    ->relationship('tingkat', 'singkatan')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('jenisSeleksi', [])),

                DatePicker::make('tanggal_awal')
                    ->label('Tanggal Awal')
                    ->required(),

                DatePicker::make('tanggal_akhir')
                    ->label('Tanggal Akhir')
                    ->required(),

                DatePicker::make('registrasi_ulang_awal')
                    ->label('Tanggal Awal Registrasi Ulang')
                    ->required(),

                DatePicker::make('registrasi_ulang_akhir')
                    ->label('Tanggal Akhir Registrasi Ulang')
                    ->required(),

                DatePicker::make('tanggal_awal_seleksi')
                    ->label('Tanggal Awal Seleksi')
                    ->required(),

                DatePicker::make('tanggal_akhir_seleksi')
                    ->label('Tanggal Akhir Seleksi')
                    ->required(),

                DatePicker::make('tanggal_pengumuman')
                    ->label('Tanggal Pengumuman Seleksi')
                    ->required(),

                MultiSelect::make('jenisSeleksi')
                    ->label('Jenis Seleksi')
                    ->options(function (callable $get) {
                        $tingkatId = $get('tingkat_id');
                
                        if (!$tingkatId) {
                            return [];
                        }
                
                        return \App\Models\JenisSeleksi::whereHas('tingkat', function ($query) use ($tingkatId) {
                            $query->where('tingkat_id', $tingkatId);
                        })->pluck('nama', 'id');
                    })
                    ->afterStateHydrated(function ($set, $state, $record) {
                        if ($record) {
                            $set('jenisSeleksi', $record->jenisSeleksi->pluck('id')->toArray());
                        }
                    })
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->required()
                                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Gelombang::with('jenisSeleksi', 'tahunAjaran', 'tingkat'))
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Gelombang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahunAjaran.tahun_ajaran')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tingkat.singkatan')
                    ->label('Tingkat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_awal')
                    ->label('Pendaftaran Awal')->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_akhir')
                    ->label('Pendaftaran Akhir')->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registrasi_ulang_awal')
                    ->label('Awal Registrasi Ulang')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('registrasi_ulang_akhir')
                    ->label('Akhir Registrasi Ulang')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tanggal_awal_seleksi')
                    ->label('Awal Seleksi')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tanggal_akhir_seleksi')
                    ->label('Akhir Seleksi')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tanggal_pengumuman')
                    ->label('Pengumuman')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('jenisSeleksi')
                    ->label('Jenis Seleksi')
                    ->formatStateUsing(fn($record) => 
                        $record->jenisSeleksi->isEmpty()
                            ? '-'
                            : '<ul style="padding-left: 1rem; margin: 0;">' .
                                $record->jenisSeleksi->map(fn($js) => "<li>{$js->nama}</li>")->join('') .
                              '</ul>'
                    )
                    ->html()
                    ->toggleable(isToggledHiddenByDefault: true),

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
            'index' => Pages\ListGelombangs::route('/'),
            'create' => Pages\CreateGelombang::route('/create'),
            'edit' => Pages\EditGelombang::route('/{record}/edit'),
        ];
    }
}
