<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\Session;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameMatch>
 */
class GameMatchFactory extends Factory
{
    protected $model = GameMatch::class;

    public function definition(): array
    {
        $homeScore = fake()->numberBetween(0, 5);
        $awayScore = fake()->numberBetween(0, 5);

        return [
            'session_id' => Session::factory(),
            'home_team_id' => Team::factory(),
            'away_team_id' => Team::factory(),
            'winner_team_id' => null,
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'started_at' => now()->subMinutes(30),
            'ended_at' => now(),
        ];
    }
}
