<?php

namespace Database\Factories;

use App\Models\Assist;
use App\Models\Goal;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assist>
 */
class AssistFactory extends Factory
{
    protected $model = Assist::class;

    public function definition(): array
    {
        return [
            'goal_id' => Goal::factory(),
            'player_id' => Player::factory(),
            'created_at' => now(),
        ];
    }
}
