<?php

namespace App\Filament\Resources\GelombangResource\Pages;

use App\Filament\Resources\GelombangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGelombang extends EditRecord
{
    protected static string $resource = GelombangResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function afterSave(): void
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
