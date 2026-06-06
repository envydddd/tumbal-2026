<?php

namespace App\Filament\Admin\Resources\BilliardTableResource\Pages;

use App\Filament\Admin\Resources\BilliardTableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBilliardTables extends ListRecords
{
    protected static string $resource = BilliardTableResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
