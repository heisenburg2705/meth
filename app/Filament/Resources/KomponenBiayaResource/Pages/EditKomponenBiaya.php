<?php

namespace App\Filament\Resources\KomponenBiayaResource\Pages;

use App\Filament\Resources\KomponenBiayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomponenBiaya extends EditRecord
{
    protected static string $resource = KomponenBiayaResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $model = static::getModel()::with(['gelombang', 'jalur', 'subJalur', 'tingkat', 'kelas'])->find($data['id']);

        return [
            ...$data,
            'gelombang' => $model->gelombang->pluck('id')->toArray(),
            'jalur' => $model->jalur->pluck('id')->toArray(),
            'subJalur' => $model->subJalur->pluck('id')->toArray(),
            'tingkat' => $model->tingkat->pluck('id')->toArray(),
            'kelas' => $model->kelas->pluck('id')->toArray(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        $record->gelombang()->sync($this->form->getState()['gelombang'] ?? []);
        $record->jalur()->sync($this->form->getState()['jalur'] ?? []);
        $record->subJalur()->sync($this->form->getState()['subJalur'] ?? []);
        $record->tingkat()->sync($this->form->getState()['tingkat'] ?? []);
        $record->kelas()->sync($this->form->getState()['kelas'] ?? []);
    }
}
