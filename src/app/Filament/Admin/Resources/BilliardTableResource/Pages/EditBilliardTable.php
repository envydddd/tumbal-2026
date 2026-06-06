<?php

namespace App\Filament\Admin\Resources\BilliardTableResource\Pages;

use App\Filament\Admin\Resources\BilliardTableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBilliardTable extends EditRecord
{
    protected static string $resource = BilliardTableResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
