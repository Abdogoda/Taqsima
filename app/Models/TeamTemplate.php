<?php

namespace App\Models;

use Database\Factories\TeamTemplateFactory;
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
 * @property string|null $color
 * @property string|null $logo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class TeamTemplate extends Model
{
    /** @use HasFactory<TeamTemplateFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'color',
        'logo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'team_template_players')
            ->withTimestamps();
    }

    public function teamTemplatePlayers(): HasMany
    {
        return $this->hasMany(TeamTemplatePlayer::class);
    }

    public function addPlayer(Player|int $player): void
    {
        $playerId = $player instanceof Player ? $player->id : $player;
        $this->players()->syncWithoutDetaching([$playerId]);
    }

    public function removePlayer(Player|int $player): void
    {
        $playerId = $player instanceof Player ? $player->id : $player;
        $this->players()->detach($playerId);
    }
}
