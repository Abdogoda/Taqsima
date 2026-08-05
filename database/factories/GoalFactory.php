<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\Goal;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Goal>
 */
class GoalFactory extends Factory
{
    protected $model = Goal::class;

    public function definition(): array
    {
        return [
            'match_id' => GameMatch::factory(),
            'team_id' => Team::factory(),
            'player_id' => Player::factory(),
            'created_at' => now(),
        ];
    }
}
