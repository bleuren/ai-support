<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Team;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

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

        $this->call([
            ShieldSeeder::class,
            QuestionSeeder::class,
        ]);

        $user->assignRole('super_admin');
        $user->teams()->attach($teamOmg);
        $user->teams()->attach($teamAitago);

        $users = User::factory(10)->create()->each(function ($user) {
            $user->assignRole('panel_user');
            $team = Team::inRandomOrder()->first();
            $user->teams()->attach($team);
        });
    }
}
