<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory(10)->create()->each(function ($user) {
            $user->assignRole('panel_user');
            $team = Team::inRandomOrder()->first();
            $user->teams()->attach($team);
        });

        $teamOmg = Team::factory()->create([
            'name' => 'OMG',
            'personal_team' => false,
            'user_id' => 1,
        ]);

        $teamAitago = Team::factory()->create([
            'name' => 'Aitago',
            'personal_team' => false,
            'user_id' => 1,
        ]);
    }
}
