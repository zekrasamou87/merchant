<?php

namespace App\Filament\Resources\ApplicationRequestResource\Pages;

use App\Filament\Resources\ApplicationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplicationRequest extends EditRecord
{
    protected static string $resource = ApplicationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
