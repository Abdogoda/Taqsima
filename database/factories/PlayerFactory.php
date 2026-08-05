<?php

namespace Database\Factories;

use App\Enums\PlayerPosition;
use App\Models\Player;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition(): array
    {
        $arabicFirstNames = ['Mohamed', 'Ahmed', 'Mahmoud', 'Omar', 'Karim', 'Youssef', 'Ali', 'Hassan', 'Hussein', 'Khaled', 'Tarek', 'Amr', 'Ziad', 'Mostafa', 'Marwan', 'Sherif', 'Nader', 'Ibrahim'];
        $arabicLastNames = ['Salah', 'Zaki', 'El-Sayed', 'Fathy', 'Naguib', 'Sobhi', 'Abdelrahman', 'Ghanem', 'Soliman', 'Kassem', 'Kamel', 'Radi', 'Hamdy', 'Mansour', 'Tawfik'];

        $name = fake()->randomElement($arabicFirstNames).' '.fake()->randomElement($arabicLastNames);
        $position = fake()->randomElement(PlayerPosition::cases());
        $matches = fake()->numberBetween(5, 50);
        $wins = fake()->numberBetween(0, $matches);
        $losses = fake()->numberBetween(0, $matches - $wins);
        $draws = $matches - $wins - $losses;

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'photo' => 'https://api.dicebear.com/7.x/bottts/svg?seed='.urlencode($name),
            'overall' => fake()->numberBetween(60, 95),
            'position' => $position->value,
            'is_injured' => fake()->boolean(10),
            'is_available' => true,
            'matches_played' => $matches,
            'wins' => $wins,
            'losses' => $losses,
            'draws' => $draws,
            'goals' => fake()->numberBetween(0, 40),
            'assists' => fake()->numberBetween(0, 30),
            'own_goals' => fake()->numberBetween(0, 3),
            'clean_sheets' => $position === PlayerPosition::GK ? fake()->numberBetween(1, 15) : 0,
            'attendance' => fake()->numberBetween(5, 50),
            'minutes_played' => $matches * fake()->numberBetween(40, 60),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
