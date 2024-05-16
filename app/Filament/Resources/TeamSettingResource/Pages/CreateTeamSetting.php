<?php

declare(strict_types=1);

namespace App\Filament\Resources\TeamSettingResource\Pages;

use App\Filament\Resources\TeamSettingResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateTeamSetting extends CreateRecord
{
    protected static string $resource = TeamSettingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament-panels::resources/pages/create-record.title', [
            'label' => __(static::getResource()::getTitleCaseModelLabel()),
        ]);
    }
}
