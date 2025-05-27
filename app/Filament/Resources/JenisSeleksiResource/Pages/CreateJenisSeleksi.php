<?php

namespace App\Filament\Resources\JenisSeleksiResource\Pages;

use App\Filament\Resources\JenisSeleksiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\QueryException;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use App\Models\Tingkat;
use Illuminate\Support\Facades\DB;

class CreateJenisSeleksi extends CreateRecord
{
    protected static string $resource = JenisSeleksiResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return DB::transaction(function () use ($data) {

            if ($this->getModel()::where('kode', $data['kode'])->exists()) {
                Notification::make()
                    ->title('Gagal Menyimpan')
                    ->danger()
                    ->body('Kode sudah digunakan.')
                    ->send();
    
                // Throw validation exception agar proses batal
                throw ValidationException::withMessages([
                    'kode' => 'Kode sudah digunakan.',
                ]);
            }
            
            // Buat record utama
            $record = $this->getModel()::create($data);

            // Simpan relasi tingkat + urutan
            $this->saveTingkatUrutan($record);

            return $record;
        });
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
}
