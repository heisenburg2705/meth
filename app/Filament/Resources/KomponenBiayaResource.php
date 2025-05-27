<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KomponenBiayaResource\Pages;
use App\Filament\Resources\KomponenBiayaResource\RelationManagers;
use App\Models\KomponenBiaya;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\RawJs;
use Illuminate\Validation\Rule;

class KomponenBiayaResource extends Resource
{
    protected static ?string $model = KomponenBiaya::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $slug = 'komponen-biaya';
    protected static ?string $pluralLabel = 'Komponen biaya';
    protected static ?string $navigationGroup = 'Setup Billing';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    ->required()
                    ->rule(function ($record) {
                        return Rule::unique('komponen_biaya', 'kode')
                            ->ignore($record?->id)
                            ->whereNull('deleted_at');
                    })
                    ->maxLength(50),

                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(100),
                    
                Forms\Components\TextInput::make('biaya')
                    ->label('Biaya')
                    ->prefix('Rp')
                    ->numeric()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Radio::make('jenis_biaya')
                    ->options([
                        'pendaftaran' => 'Pendaftaran',
                        'registrasi' => 'Registrasi Ulang',
                    ])
                    ->inline()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Radio::make('untuk_alumni')
                    ->options([
                        0 => 'Tidak',
                        1 => 'Ya',
                    ])
                    ->inline()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Radio::make('jenis')
                    ->options([
                        'wajib' => 'Wajib',
                        'tambahan' => 'Tambahan',
                    ])
                    ->inline()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('tingkat')
                    ->multiple()
                    ->options(function () {
                        return \App\Models\Tingkat::pluck('singkatan', 'id');
                    })
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $get, $state) {
                        $selectedTingkat = (array) $get('tingkat');

                        $kelas = (array) $get('kelas');
                        $validKelas = \App\Models\Kelas::whereIn('tingkat_id', $selectedTingkat)
                            ->pluck('id')
                            ->toArray();
                        $filteredKelas = array_values(array_intersect($kelas, $validKelas));
                        if ($filteredKelas != $kelas) {
                            $set('kelas', $filteredKelas);
                        }

                        $gelombang = (array) $get('gelombang');
                        $validGelombang = \App\Models\Gelombang::whereIn('tingkat_id', $selectedTingkat)
                            ->pluck('id')
                            ->toArray();
                        $filteredGelombang = array_values(array_intersect($gelombang, $validGelombang));
                        if ($filteredGelombang != $gelombang) {
                            $set('gelombang', $filteredGelombang);
                        }
                    }),

                Forms\Components\Select::make('kelas')
                    ->multiple()
                    ->options(function (callable $get) {
                        $tingkatIds = $get('tingkat');

                        if (!$tingkatIds) return [];

                        return \App\Models\Kelas::whereIn('tingkat_id', (array) $tingkatIds)
                            ->pluck('nama', 'id');
                    })
                    ->label('Kelas (opsional)')
                    ->disabled(fn (callable $get) => empty($get('tingkat'))),
                
                Forms\Components\Select::make('jalur')
                    ->multiple()
                    ->options(function () {
                        return \App\Models\Jalur::pluck('nama', 'id');
                    })
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $selectedJalur = (array) $get('jalur');
                        $selectedSubJalur = (array) $get('subJalur');
                    
                        $validSubJalur = \App\Models\JalurDetail::whereIn('jalur_id', $selectedJalur)
                            ->pluck('id')
                            ->toArray();
                    
                        $filteredSubJalur = array_values(array_intersect($selectedSubJalur, $validSubJalur));

                        if ($filteredSubJalur != $selectedSubJalur) {
                            $set('subJalur', $filteredSubJalur);
                        }
                    }),
                
                Forms\Components\Select::make('subJalur')
                    ->label('Sub Jalur')
                    ->multiple()
                    ->options(function (callable $get) {
                        $jalurIds = $get('jalur');
                        if (!$jalurIds) return [];
                
                        return \App\Models\JalurDetail::whereIn('jalur_id', (array) $jalurIds)
                            ->pluck('nama', 'id');
                    })
                    ->required()
                    ->disabled(fn (callable $get) => empty($get('jalur'))),

                Forms\Components\Select::make('gelombang')
                    ->multiple()
                    ->options(function (callable $get) {
                        $tingkatIds = $get('tingkat');

                        if (!$tingkatIds) return [];

                        return \App\Models\Gelombang::whereIn('tingkat_id', (array) $tingkatIds)
                            ->pluck('nama', 'id');
                    })
                    ->required()
                    ->disabled(fn (callable $get) => empty($get('tingkat'))),
                
                Forms\Components\Select::make('jenis_kelamin')
                    ->multiple()
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->label('Jenis Kelamin (opsional)')
                    ->placeholder('Pilih jika dibatasi'),

                // Forms\Components\TextInput::make('urut')
                //     ->numeric()
                //     ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')->searchable(),
                TextColumn::make('nama')->searchable(),
                TextColumn::make('biaya')->money('IDR', true),
                TextColumn::make('jenis_biaya')->label('Jenis Biaya'),
                TextColumn::make('jenis')->label('Wajib/Tambahan'),
                TextColumn::make('tingkat.singkatan')
                    ->label('Tingkat')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('untuk_alumni')
                    ->boolean()
                    ->label('Untuk Alumni')
                    ->alignCenter(),
                TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jalur.nama')
                    ->label('Jalur')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('subJalur.nama')
                    ->label('Sub Jalur')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gelombang.nama')
                    ->label('Gelombang')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListKomponenBiayas::route('/'),
            'create' => Pages\CreateKomponenBiaya::route('/create'),
            'edit' => Pages\EditKomponenBiaya::route('/{record}/edit'),
        ];
    }
}
