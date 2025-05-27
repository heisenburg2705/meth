<?php

namespace App\Filament\Resources\SyaratResource\Pages;

use App\Filament\Resources\SyaratResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSyarat extends CreateRecord
{
    protected static string $resource = SyaratResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
