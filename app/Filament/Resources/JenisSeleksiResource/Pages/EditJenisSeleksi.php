<?php

namespace App\Filament\Resources\JenisSeleksiResource\Pages;

use App\Filament\Resources\JenisSeleksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\JenisSeleksi;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use App\Models\Tingkat;


class EditJenisSeleksi extends EditRecord
{
    protected static string $resource = JenisSeleksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormModel(): JenisSeleksi
    {
        return $this->record;
    }

    protected function saveTingkatUrutan($record): void
    {
        $record->tingkat()->detach();

        $items = $this->form->getState()['tingkat_urutan'] ?? [];

        $keys = [];
        foreach ($items as $item) {
            $key = $item['tingkat_id'] . '-' . $item['urutan'];

            if (in_array($key, $keys)) {
                throw ValidationException::withMessages([
                    'tingkat_urutan' => "Urutan {$item['urutan']} untuk tingkat ID {$item['tingkat_id']} duplikat di dalam form.",
                ]);
            }

            $keys[] = $key;

            try {
                $record->tingkat()->attach($item['tingkat_id'], [
                    'urutan' => $item['urutan'],
                ]);
            } catch (QueryException $e) {
                if ($e->getCode() == '23000') {
                    $tingkat = Tingkat::find($item['tingkat_id']);
            
                    Notification::make()
                        ->title('Gagal Menyimpan')
                        ->body("Kombinasi Tingkat {$tingkat->singkatan} dan Urutan {$item['urutan']} sudah ada.")
                        ->danger()
                        ->persistent()
                        ->send();
            
                    throw ValidationException::withMessages([
                        'tingkat_urutan' => "Kombinasi Tingkat {$tingkat->singkatan} dan Urutan {$item['urutan']} sudah digunakan.",
                    ]);
                }

                throw $e;
            }
        }
    }

    protected function afterCreate(): void
    {
        DB::beginTransaction();

        try {
            $this->saveTingkatUrutan($this->record);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            // Rethrow agar error tampil di form
            throw $e;
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['tingkat_urutan'] = $this->record->tingkat->map(function ($tingkat) {
            return [
                'tingkat_id' => $tingkat->id,
                'urutan' => $tingkat->pivot->urutan,
            ];
        })->toArray();

        return $data;
    }
}
