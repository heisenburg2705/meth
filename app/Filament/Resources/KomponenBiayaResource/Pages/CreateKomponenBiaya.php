<?php

namespace App\Filament\Resources\KomponenBiayaResource\Pages;

use App\Filament\Resources\KomponenBiayaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKomponenBiaya extends CreateRecord
{
    protected static string $resource = KomponenBiayaResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        $record->gelombang()->sync($this->form->getState()['gelombang'] ?? []);
        $record->jalur()->sync($this->form->getState()['jalur'] ?? []);
        $record->subJalur()->sync($this->form->getState()['subJalur'] ?? []);
        $record->tingkat()->sync($this->form->getState()['tingkat'] ?? []);
        $record->kelas()->sync($this->form->getState()['kelas'] ?? []);
    }
}
