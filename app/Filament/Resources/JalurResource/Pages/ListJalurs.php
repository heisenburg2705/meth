<?php

namespace App\Filament\Resources\JalurResource\Pages;

use App\Filament\Resources\JalurResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJalurs extends ListRecords
{
    protected static string $resource = JalurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
