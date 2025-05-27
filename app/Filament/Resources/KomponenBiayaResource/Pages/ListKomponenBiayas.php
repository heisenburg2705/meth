<?php

namespace App\Filament\Resources\KomponenBiayaResource\Pages;

use App\Filament\Resources\KomponenBiayaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKomponenBiayas extends ListRecords
{
    protected static string $resource = KomponenBiayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
