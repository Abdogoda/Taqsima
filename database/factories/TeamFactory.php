<?php

namespace Database\Factories;

use App\Models\Session;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        $teamPresets = [
            ['name' => 'Red Team', 'color' => '#EF4444'],
            ['name' => 'White Team', 'color' => '#FFFFFF'],
            ['name' => 'Blue Team', 'color' => '#3B82F6'],
            ['name' => 'Green Team', 'color' => '#10B981'],
            ['name' => 'Yellow Team', 'color' => '#F59E0B'],
            ['name' => 'Bench', 'color' => '#6B7280'],
        ];

        $preset = fake()->randomElement($teamPresets);

        return [
            'session_id' => Session::factory(),
            'name' => $preset['name'],
            'color' => $preset['color'],
            'logo' => null,
            'display_order' => fake()->numberBetween(1, 4),
        ];
    }
}
