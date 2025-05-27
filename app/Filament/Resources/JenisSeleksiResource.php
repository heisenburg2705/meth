<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisSeleksiResource\Pages;
use App\Filament\Resources\JenisSeleksiResource\RelationManagers;
use App\Models\JenisSeleksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use App\Models\Tingkat;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class JenisSeleksiResource extends Resource
{
    protected static ?string $model = JenisSeleksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $slug = 'jenis-seleksi';
    protected static ?string $pluralLabel = 'Jenis Seleksi';
    protected static ?string $navigationGroup = 'Setup Master';
    protected static ?int $navigationSort = 4;


    public static function mutateFormDataBeforeSave(array $data): array
    {
        $tingkatUrutan = $data['tingkat_urutan'] ?? [];

        $kombinasi = [];

        foreach ($tingkatUrutan as $item) {
            $key = $item['tingkat_id'] . '-' . $item['urutan'];

            if (in_array($key, $kombinasi)) {
                throw ValidationException::withMessages([
                    'tingkat_urutan' => 'Duplikasi kombinasi Tingkat dan Urutan ditemukan di form.',
                ]);
            }

            $exists = DB::table('jenis_seleksi_tingkat')
                ->where('tingkat_id', $item['tingkat_id'])
                ->where('urutan', $item['urutan'])
                ->when(request()->route('record'), function ($query) {
                    $query->where('jenis_seleksi_id', '!=', request()->route('record'));
                })
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'tingkat_urutan' => "Urutan {$item['urutan']} untuk tingkat ID {$item['tingkat_id']} sudah digunakan.",
                ]);
            }

            $kombinasi[] = $key;
        }

        return $data;
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('nama')->required(),
            TextInput::make('kode')
                ->required()
                ->label('Kode'),

            Repeater::make('tingkat_urutan')
                ->label('Tingkat dan Urutan')
                ->schema([
                    Select::make('tingkat_id')
                        ->label('Tingkat')
                        ->options(Tingkat::all()->pluck('singkatan', 'id'))
                        ->required(),
                    TextInput::make('urutan')
                        ->label('Urutan')
                        ->numeric()
                        ->required(),
                ])
                ->columns(2)
                ->minItems(1)
                ->required()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(JenisSeleksi::with('tingkat'))
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Jenis Seleksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kode')
                    ->label('Kode Jenis Seleksi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tingkat')
                    ->label('Tingkat dan Urutan')
                    ->formatStateUsing(fn($record) => 
                        $record->tingkat->isEmpty()
                            ? '-'
                            : '<ul style="padding-left: 1rem; margin: 0;">' .
                                $record->tingkat->map(fn($t) => "<li>{$t->singkatan} ({$t->pivot->urutan})</li>")->join('') .
                              '</ul>'
                    )
                    ->html(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at') 
                    ->dateTime()
                    ->sortable()
                    ->searchable()
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
            'index' => Pages\ListJenisSeleksis::route('/'),
            'create' => Pages\CreateJenisSeleksi::route('/create'),
            'edit' => Pages\EditJenisSeleksi::route('/{record}/edit'),
        ];
    }
}
