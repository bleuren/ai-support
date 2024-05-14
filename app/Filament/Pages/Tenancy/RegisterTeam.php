<?php

declare(strict_types=1);

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Events\AddingTeam;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return __('Register team');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($livewire, $component) {
                        $livewire->validateOnly($component->getStatePath());
                    }),
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createTeam');

        $user = auth()->user();

        AddingTeam::dispatch($user);

        $user->switchTeam($team = $user->ownedTeams()->create([
            'name' => $data['name'],
            'personal_team' => false,
        ]));

        return $team;
    }
}
