<?php

namespace Tests\Feature;

use App\Models\Assist;
use App\Models\GameMatch;
use App\Models\Goal;
use App\Models\Player;
use App\Models\Session;
use App\Models\SessionPlayer;
use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\TeamTemplate;
use App\Models\TeamTemplatePlayer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_model_relationships_work_correctly(): void
    {
        $this->seed();

        // 1. User relationships
        $user = User::firstOrFail();
        $this->assertGreaterThan(0, $user->players()->count());
        $this->assertGreaterThan(0, $user->teamTemplates()->count());
        $this->assertGreaterThan(0, $user->sessions()->count());

        // 2. Player relationships
        $player = Player::firstOrFail();
        $this->assertInstanceOf(User::class, $player->user);
        $this->assertNotNull($player->teamTemplates);
        $this->assertNotNull($player->sessions);
        $this->assertNotNull($player->sessionPlayers);
        $this->assertNotNull($player->teams);
        $this->assertNotNull($player->teamPlayers);
        $this->assertNotNull($player->goals);
        $this->assertNotNull($player->assists);

        // 3. TeamTemplate & TeamTemplatePlayer relationships
        $template = TeamTemplate::firstOrFail();
        $this->assertInstanceOf(User::class, $template->user);
        $this->assertGreaterThan(0, $template->players()->count());
        $this->assertGreaterThan(0, $template->teamTemplatePlayers()->count());

        $ttPlayer = TeamTemplatePlayer::firstOrFail();
        $this->assertInstanceOf(TeamTemplate::class, $ttPlayer->teamTemplate);
        $this->assertInstanceOf(Player::class, $ttPlayer->player);

        // 4. Session & SessionPlayer relationships
        $session = Session::has('matches')->firstOrFail();
        $this->assertInstanceOf(User::class, $session->user);
        $this->assertGreaterThan(0, $session->players()->count());
        $this->assertGreaterThan(0, $session->sessionPlayers()->count());
        $this->assertGreaterThan(0, $session->teams()->count());
        $this->assertGreaterThan(0, $session->matches()->count());

        $sPlayer = SessionPlayer::firstOrFail();
        $this->assertInstanceOf(Session::class, $sPlayer->session);
        $this->assertInstanceOf(Player::class, $sPlayer->player);

        // 5. Team & TeamPlayer relationships
        $team = Team::firstOrFail();
        $this->assertInstanceOf(Session::class, $team->session);
        $this->assertNotNull($team->players);
        $this->assertNotNull($team->teamPlayers);
        $this->assertNotNull($team->homeMatches);
        $this->assertNotNull($team->awayMatches);

        $tPlayer = TeamPlayer::firstOrFail();
        $this->assertInstanceOf(Team::class, $tPlayer->team);
        $this->assertInstanceOf(Player::class, $tPlayer->player);

        // 6. GameMatch relationships
        $match = GameMatch::firstOrFail();
        $this->assertInstanceOf(Session::class, $match->session);
        $this->assertInstanceOf(Team::class, $match->homeTeam);
        $this->assertInstanceOf(Team::class, $match->awayTeam);
        $this->assertGreaterThan(0, $match->goals()->count());

        // 7. Goal & Assist relationships
        $goal = Goal::has('assist')->firstOrFail();
        $this->assertInstanceOf(GameMatch::class, $goal->match);
        $this->assertInstanceOf(Team::class, $goal->team);
        $this->assertInstanceOf(Player::class, $goal->player);
        $this->assertInstanceOf(Assist::class, $goal->assist);

        $assist = Assist::firstOrFail();
        $this->assertInstanceOf(Goal::class, $assist->goal);
        $this->assertInstanceOf(Player::class, $assist->player);
    }
}
