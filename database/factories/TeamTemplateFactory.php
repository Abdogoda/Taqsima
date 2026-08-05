<?php

namespace Database\Factories;

use App\Models\TeamTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamTemplate>
 */
class TeamTemplateFactory extends Factory
{
    protected $model = TeamTemplate::class;

    public function definition(): array
    {
        $templateNames = [
            'Friday Night Legends',
            'Al-Ahly Friends',
            'Zamalek Regulars',
            'El-Tagamoa Ballers',
            'Maadi Superstars',
            'Alexandria Coast Squad',
        ];

        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement($templateNames),
            'color' => fake()->hexColor(),
            'logo' => null,
        ];
    }
}
