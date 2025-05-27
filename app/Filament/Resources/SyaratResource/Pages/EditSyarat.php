<?php

namespace App\Filament\Resources\SyaratResource\Pages;

use App\Filament\Resources\SyaratResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSyarat extends EditRecord
{
    protected static string $resource = SyaratResource::class;

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
}
