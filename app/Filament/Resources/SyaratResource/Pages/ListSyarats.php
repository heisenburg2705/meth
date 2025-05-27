<?php

namespace App\Filament\Resources\SyaratResource\Pages;

use App\Filament\Resources\SyaratResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSyarats extends ListRecords
{
    protected static string $resource = SyaratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
