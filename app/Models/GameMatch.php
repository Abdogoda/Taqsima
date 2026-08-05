<?php

namespace App\Models;

use Database\Factories\GameMatchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $session_id
 * @property int $home_team_id
 * @property int $away_team_id
 * @property int|null $winner_team_id
 * @property int $home_score
 * @property int $away_score
 * @property Carbon|null $started_at
 * @property Carbon|null $ended_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class GameMatch extends Model
{
    /** @use HasFactory<GameMatchFactory> */
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'session_id',
        'home_team_id',
        'away_team_id',
        'winner_team_id',
        'home_score',
        'away_score',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'home_score' => 'integer',
            'away_score' => 'integer',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    // --- Relationships ---

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function winnerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class, 'match_id');
    }

    // --- Helper Functions ---

    public function recalculateScore(): bool
    {
        $homeScore = $this->goals()->where('team_id', $this->home_team_id)->count();
        $awayScore = $this->goals()->where('team_id', $this->away_team_id)->count();

        $winnerId = null;
        if ($homeScore > $awayScore) {
            $winnerId = $this->home_team_id;
        } elseif ($awayScore > $homeScore) {
            $winnerId = $this->away_team_id;
        }

        return $this->update([
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'winner_team_id' => $winnerId,
        ]);
    }

    public function finishMatch(?int $winnerTeamId = null): bool
    {
        $winnerId = $winnerTeamId;
        if (is_null($winnerId)) {
            if ($this->home_score > $this->away_score) {
                $winnerId = $this->home_team_id;
            } elseif ($this->away_score > $this->home_score) {
                $winnerId = $this->away_team_id;
            }
        }

        return $this->update([
            'winner_team_id' => $winnerId,
            'ended_at' => now(),
        ]);
    }
}
