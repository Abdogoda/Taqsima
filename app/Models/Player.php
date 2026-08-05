<?php

namespace App\Models;

use App\Enums\PlayerPosition;
use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $photo
 * @property int $overall
 * @property PlayerPosition|string|null $position
 * @property bool $is_injured
 * @property bool $is_available
 * @property int $matches_played
 * @property int $wins
 * @property int $losses
 * @property int $draws
 * @property int $goals
 * @property int $assists
 * @property int $own_goals
 * @property int $clean_sheets
 * @property int $attendance
 * @property int $minutes_played
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
class Player extends Model
{
    /** @use HasFactory<PlayerFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'photo',
        'overall',
        'position',
        'is_injured',
        'is_available',
        'matches_played',
        'wins',
        'losses',
        'draws',
        'goals',
        'assists',
        'own_goals',
        'clean_sheets',
        'attendance',
        'minutes_played',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'overall' => 'integer',
            'position' => PlayerPosition::class,
            'is_injured' => 'boolean',
            'is_available' => 'boolean',
            'matches_played' => 'integer',
            'wins' => 'integer',
            'losses' => 'integer',
            'draws' => 'integer',
            'goals' => 'integer',
            'assists' => 'integer',
            'own_goals' => 'integer',
            'clean_sheets' => 'integer',
            'attendance' => 'integer',
            'minutes_played' => 'integer',
        ];
    }

    // --- Relationships ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teamTemplates(): BelongsToMany
    {
        return $this->belongsToMany(TeamTemplate::class, 'team_template_players')
            ->withTimestamps();
    }

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'session_players')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function sessionPlayers(): HasMany
    {
        return $this->hasMany(SessionPlayer::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_players')
            ->withPivot('position')
            ->withTimestamps();
    }

    public function teamPlayers(): HasMany
    {
        return $this->hasMany(TeamPlayer::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    public function assists(): HasMany
    {
        return $this->hasMany(Assist::class);
    }

    // --- Scopes ---

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeHealthy(Builder $query): Builder
    {
        return $query->where('is_injured', false);
    }

    public function scopeInjured(Builder $query): Builder
    {
        return $query->where('is_injured', true);
    }

    public function scopeTopScorers(Builder $query): Builder
    {
        return $query->orderByDesc('goals');
    }

    public function scopeTopAssisters(Builder $query): Builder
    {
        return $query->orderByDesc('assists');
    }

    public function scopeByPosition(Builder $query, PlayerPosition|string $position): Builder
    {
        $val = $position instanceof PlayerPosition ? $position->value : $position;

        return $query->where('position', $val);
    }

    // --- Helper Functions ---

    public function winRate(): float
    {
        if ($this->matches_played === 0) {
            return 0.0;
        }

        return round(($this->wins / $this->matches_played) * 100, 2);
    }

    public function goalContribution(): int
    {
        return $this->goals + $this->assists;
    }

    public function markAsInjured(): bool
    {
        return $this->update([
            'is_injured' => true,
            'is_available' => false,
        ]);
    }

    public function markAsAvailable(): bool
    {
        return $this->update([
            'is_injured' => false,
            'is_available' => true,
        ]);
    }

    public function recordMatchStats(
        int $goals = 0,
        int $assists = 0,
        int $ownGoals = 0,
        bool $cleanSheet = false,
        int $minutes = 0,
        string $result = 'win'
    ): bool {
        $this->matches_played += 1;
        $this->goals += $goals;
        $this->assists += $assists;
        $this->own_goals += $ownGoals;
        if ($cleanSheet) {
            $this->clean_sheets += 1;
        }
        $this->minutes_played += $minutes;

        if ($result === 'win') {
            $this->wins += 1;
        } elseif ($result === 'loss') {
            $this->losses += 1;
        } else {
            $this->draws += 1;
        }

        return $this->save();
    }
}
