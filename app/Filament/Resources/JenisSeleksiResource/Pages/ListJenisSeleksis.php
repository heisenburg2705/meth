<?php

namespace App\Filament\Resources\JenisSeleksiResource\Pages;

use App\Filament\Resources\JenisSeleksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisSeleksis extends ListRecords
{
    protected static string $resource = JenisSeleksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
