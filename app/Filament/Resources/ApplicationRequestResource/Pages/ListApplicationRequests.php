<?php

namespace App\Filament\Resources\ApplicationRequestResource\Pages;

use App\Filament\Resources\ApplicationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplicationRequests extends ListRecords
{
    protected static string $resource = ApplicationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
