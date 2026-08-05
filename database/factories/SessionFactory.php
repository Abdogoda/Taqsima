<?php

namespace Database\Factories;

use App\Enums\RotationMode;
use App\Enums\SessionStatus;
use App\Enums\TeamGenerationMode;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Session>
 */
class SessionFactory extends Factory
{
    protected $model = Session::class;

    public function definition(): array
    {
        $sessionTitles = [
            'Friday Night Football',
            'Weekly Match - New Cairo',
            'Ramadan Tournament Session',
            'Midweek Khomasi',
            'Weekend Challenge',
        ];

        $fields = [
            ['name' => 'El-Malab El-Khomasi', 'location' => 'Fifth Settlement, New Cairo'],
            ['name' => 'Nadi El-Shams Pitch 3', 'location' => 'Heliopolis, Cairo'],
            ['name' => 'Smouha Sports Club', 'location' => 'Smouha, Alexandria'],
            ['name' => 'Wadi Degla Pitch A', 'location' => 'Maadi, Cairo'],
        ];

        $field = fake()->randomElement($fields);

        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement($sessionTitles),
            'play_date' => fake()->dateTimeBetween('-1 month', '+2 weeks')->format('Y-m-d'),
            'start_time' => '20:00:00',
            'end_time' => '21:30:00',
            'duration_minutes' => 90,
            'field_name' => $field['name'],
            'field_location' => $field['location'],
            'players_per_team' => 5,
            'number_of_teams' => 3,
            'team_generation_mode' => fake()->randomElement(TeamGenerationMode::cases())->value,
            'rotation_mode' => fake()->randomElement(RotationMode::cases())->value,
            'status' => fake()->randomElement(SessionStatus::cases())->value,
            'notes' => fake()->optional(0.4)->sentence(),
        ];
    }
}
