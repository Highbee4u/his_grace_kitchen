<?php

namespace App\Filament\Resources\CateringRequestResource\Pages;

use App\Filament\Resources\CateringRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCateringRequest extends EditRecord
{
    protected static string $resource = CateringRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
