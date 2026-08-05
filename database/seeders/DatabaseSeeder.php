<?php

namespace Database\Seeders;

use App\Enums\PlayerPosition;
use App\Enums\RotationMode;
use App\Enums\SessionPlayerStatus;
use App\Enums\SessionStatus;
use App\Enums\TeamGenerationMode;
use App\Models\Assist;
use App\Models\GameMatch;
use App\Models\Goal;
use App\Models\Player;
use App\Models\Session;
use App\Models\SessionPlayer;
use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\TeamTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create main organizer account
        $organizer = User::factory()->create([
            'name' => 'Captain Ahmed Hassan',
            'phone' => '01000000000',
            'email' => 'organizer@taqsima.app',
            'password' => Hash::make('password'),
        ]);

        // 2. Create 20 realistic players in organizer library
        $playersData = [
            ['name' => 'Mohamed El-Shenawy', 'position' => PlayerPosition::GK, 'overall' => 88],
            ['name' => 'Ahmed Hegazi', 'position' => PlayerPosition::DEF, 'overall' => 84],
            ['name' => 'Mohamed Abdelmonem', 'position' => PlayerPosition::DEF, 'overall' => 85],
            ['name' => 'Ali Maaloul', 'position' => PlayerPosition::DEF, 'overall' => 86],
            ['name' => 'Akram Tawfik', 'position' => PlayerPosition::DEF, 'overall' => 82],
            ['name' => 'Hamdy Fathy', 'position' => PlayerPosition::MID, 'overall' => 83],
            ['name' => 'Imam Ashour', 'position' => PlayerPosition::MID, 'overall' => 87],
            ['name' => 'Mohamed Magdy Afsha', 'position' => PlayerPosition::MID, 'overall' => 84],
            ['name' => 'Ahmed Sayed Zizo', 'position' => PlayerPosition::FWD, 'overall' => 89],
            ['name' => 'Mohamed Salah', 'position' => PlayerPosition::FWD, 'overall' => 96],
            ['name' => 'Omar Marmoush', 'position' => PlayerPosition::FWD, 'overall' => 88],
            ['name' => 'Mostafa Mohamed', 'position' => PlayerPosition::FWD, 'overall' => 85],
            ['name' => 'Tarek Hamed', 'position' => PlayerPosition::MID, 'overall' => 81],
            ['name' => 'Mahmoud Trezeguet', 'position' => PlayerPosition::FWD, 'overall' => 86],
            ['name' => 'Hussein El-Shahat', 'position' => PlayerPosition::FWD, 'overall' => 83],
            ['name' => 'Marwan Attia', 'position' => PlayerPosition::MID, 'overall' => 82],
            ['name' => 'Yasser Ibrahim', 'position' => PlayerPosition::DEF, 'overall' => 80],
            ['name' => 'Mohamed Abou Gabal', 'position' => PlayerPosition::GK, 'overall' => 82],
            ['name' => 'Kahraba', 'position' => PlayerPosition::FWD, 'overall' => 81],
            ['name' => 'Ramadan Sobhi', 'position' => PlayerPosition::FWD, 'overall' => 82],
        ];

        $createdPlayers = collect();
        foreach ($playersData as $pData) {
            $player = Player::factory()->create([
                'user_id' => $organizer->id,
                'name' => $pData['name'],
                'position' => $pData['position']->value,
                'overall' => $pData['overall'],
                'photo' => 'https://api.dicebear.com/7.x/bottts/svg?seed='.urlencode($pData['name']),
            ]);
            $createdPlayers->push($player);
        }

        // 3. Create Team Templates
        $templateAhly = TeamTemplate::factory()->create([
            'user_id' => $organizer->id,
            'name' => 'Friday Night Legends',
            'color' => '#DC2626',
        ]);
        $templateAhly->players()->attach($createdPlayers->take(10)->pluck('id'));

        $templateZamalek = TeamTemplate::factory()->create([
            'user_id' => $organizer->id,
            'name' => 'Zamalek Friends',
            'color' => '#FFFFFF',
        ]);
        $templateZamalek->players()->attach($createdPlayers->skip(10)->take(10)->pluck('id'));

        // 4. Create a Finished Session with Teams, Matches, Goals & Assists
        $finishedSession = Session::factory()->create([
            'user_id' => $organizer->id,
            'title' => 'Friday Night Sa3a - El Malab El Khomasi',
            'play_date' => now()->subDays(2)->format('Y-m-d'),
            'start_time' => '20:00:00',
            'end_time' => '21:30:00',
            'duration_minutes' => 90,
            'field_name' => 'El-Malab El-Khomasi',
            'field_location' => 'Fifth Settlement, New Cairo',
            'players_per_team' => 5,
            'number_of_teams' => 3,
            'team_generation_mode' => TeamGenerationMode::Automatic->value,
            'rotation_mode' => RotationMode::WinnerStays->value,
            'status' => SessionStatus::Finished->value,
            'notes' => 'Great match, top performance from Salah and Zizo.',
        ]);

        // Attach Session Players
        foreach ($createdPlayers->take(15) as $index => $player) {
            SessionPlayer::create([
                'session_id' => $finishedSession->id,
                'player_id' => $player->id,
                'status' => $index < 12 ? SessionPlayerStatus::Playing->value : SessionPlayerStatus::Bench->value,
            ]);
        }

        // Create Teams inside Finished Session
        $redTeam = Team::factory()->create([
            'session_id' => $finishedSession->id,
            'name' => 'Red Team',
            'color' => '#EF4444',
            'display_order' => 1,
        ]);

        $whiteTeam = Team::factory()->create([
            'session_id' => $finishedSession->id,
            'name' => 'White Team',
            'color' => '#FFFFFF',
            'display_order' => 2,
        ]);

        $blueTeam = Team::factory()->create([
            'session_id' => $finishedSession->id,
            'name' => 'Blue Team',
            'color' => '#3B82F6',
            'display_order' => 3,
        ]);

        // Assign Players to Teams
        $redPlayers = $createdPlayers->slice(0, 5);
        foreach ($redPlayers as $p) {
            TeamPlayer::create(['team_id' => $redTeam->id, 'player_id' => $p->id, 'position' => $p->position]);
        }

        $whitePlayers = $createdPlayers->slice(5, 5);
        foreach ($whitePlayers as $p) {
            TeamPlayer::create(['team_id' => $whiteTeam->id, 'player_id' => $p->id, 'position' => $p->position]);
        }

        $bluePlayers = $createdPlayers->slice(10, 5);
        foreach ($bluePlayers as $p) {
            TeamPlayer::create(['team_id' => $blueTeam->id, 'player_id' => $p->id, 'position' => $p->position]);
        }

        // Create Match 1: Red vs White
        $match1 = GameMatch::create([
            'session_id' => $finishedSession->id,
            'home_team_id' => $redTeam->id,
            'away_team_id' => $whiteTeam->id,
            'started_at' => now()->subHours(2),
            'ended_at' => now()->subHours(1)->subMinutes(40),
        ]);

        // Goals for Match 1
        $goal1 = Goal::create([
            'match_id' => $match1->id,
            'team_id' => $redTeam->id,
            'player_id' => $redPlayers->where('name', 'Mohamed Salah')->first()?->id ?? $redPlayers->first()->id,
            'created_at' => now()->subHours(2)->addMinutes(5),
        ]);

        Assist::create([
            'goal_id' => $goal1->id,
            'player_id' => $redPlayers->where('name', 'Ahmed Sayed Zizo')->first()?->id ?? $redPlayers->last()->id,
            'created_at' => $goal1->created_at,
        ]);

        $goal2 = Goal::create([
            'match_id' => $match1->id,
            'team_id' => $redTeam->id,
            'player_id' => $redPlayers->where('name', 'Mohamed Salah')->first()?->id ?? $redPlayers->first()->id,
            'created_at' => now()->subHours(2)->addMinutes(12),
        ]);

        $goal3 = Goal::create([
            'match_id' => $match1->id,
            'team_id' => $whiteTeam->id,
            'player_id' => $whitePlayers->where('name', 'Omar Marmoush')->first()?->id ?? $whitePlayers->first()->id,
            'created_at' => now()->subHours(2)->addMinutes(15),
        ]);

        $match1->recalculateScore();
        $match1->finishMatch();

        // 5. Create Live Session
        $liveSession = Session::factory()->create([
            'user_id' => $organizer->id,
            'title' => 'Weekly Match - Nadi El Shams',
            'play_date' => now()->format('Y-m-d'),
            'start_time' => now()->subMinutes(30)->format('H:i:s'),
            'duration_minutes' => 60,
            'field_name' => 'Nadi El-Shams Pitch 3',
            'field_location' => 'Heliopolis, Cairo',
            'players_per_team' => 5,
            'number_of_teams' => 2,
            'team_generation_mode' => TeamGenerationMode::Manual->value,
            'rotation_mode' => RotationMode::FixedTeams->value,
            'status' => SessionStatus::Live->value,
        ]);

        // 6. Create Draft Session
        Session::factory()->create([
            'user_id' => $organizer->id,
            'title' => 'Ramadan Tournament - Wadi Degla',
            'play_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '21:00:00',
            'duration_minutes' => 120,
            'status' => SessionStatus::Draft->value,
        ]);
    }
}
