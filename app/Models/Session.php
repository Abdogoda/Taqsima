<?php

namespace App\Models;

use App\Enums\RotationMode;
use App\Enums\SessionStatus;
use App\Enums\TeamGenerationMode;
use Database\Factories\SessionFactory;
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
 * @property string $title
 * @property Carbon|string $play_date
 * @property string $start_time
 * @property string|null $end_time
 * @property int $duration_minutes
 * @property string|null $field_name
 * @property string|null $field_location
 * @property int $players_per_team
 * @property int $number_of_teams
 * @property TeamGenerationMode|string $team_generation_mode
 * @property RotationMode|string $rotation_mode
 * @property SessionStatus|string $status
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Session extends Model
{
    /** @use HasFactory<SessionFactory> */
    use HasFactory;

    protected $table = 'game_sessions';

    protected $fillable = [
        'user_id',
        'title',
        'play_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'field_name',
        'field_location',
        'players_per_team',
        'number_of_teams',
        'team_generation_mode',
        'rotation_mode',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'play_date' => 'date',
            'duration_minutes' => 'integer',
            'players_per_team' => 'integer',
            'number_of_teams' => 'integer',
            'team_generation_mode' => TeamGenerationMode::class,
            'rotation_mode' => RotationMode::class,
            'status' => SessionStatus::class,
        ];
    }

    // --- Relationships ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'session_players')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function sessionPlayers(): HasMany
    {
        return $this->hasMany(SessionPlayer::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class);
    }

    // --- Scopes ---

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', SessionStatus::Draft->value);
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', SessionStatus::Live->value);
    }

    public function scopeFinished(Builder $query): Builder
    {
        return $query->where('status', SessionStatus::Finished->value);
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', SessionStatus::Cancelled->value);
    }

    // --- Helper Functions ---

    public function startSession(): bool
    {
        return $this->update([
            'status' => SessionStatus::Live->value,
        ]);
    }

    public function finishSession(): bool
    {
        return $this->update([
            'status' => SessionStatus::Finished->value,
            'end_time' => $this->end_time ?? now()->format('H:i:s'),
        ]);
    }

    public function cancelSession(): bool
    {
        return $this->update([
            'status' => SessionStatus::Cancelled->value,
        ]);
    }
}
