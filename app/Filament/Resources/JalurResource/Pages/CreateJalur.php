<?php

namespace App\Filament\Resources\JalurResource\Pages;

use App\Filament\Resources\JalurResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJalur extends CreateRecord
{
    protected static string $resource = JalurResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
