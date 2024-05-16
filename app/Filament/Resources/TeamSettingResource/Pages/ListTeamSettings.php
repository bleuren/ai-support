<?php

declare(strict_types=1);

namespace App\Filament\Resources\TeamSettingResource\Pages;

use App\Filament\Resources\TeamSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListTeamSettings extends ListRecords
{
    protected static string $resource = TeamSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return static::$title ?? __(static::getResource()::getTitleCasePluralModelLabel());
    }
}
