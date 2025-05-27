<?php

namespace App\Filament\Resources\GelombangResource\Pages;

use App\Filament\Resources\GelombangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGelombang extends CreateRecord
{
    protected static string $resource = GelombangResource::class;
    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        $jenisSeleksiIds = $this->form->getState()['jenisSeleksi'] ?? [];
        $this->record->jenisSeleksi()->sync($jenisSeleksiIds);
    }

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
