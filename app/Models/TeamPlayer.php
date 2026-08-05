<?php

namespace App\Models;

use App\Enums\PlayerPosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $team_id
 * @property int $player_id
 * @property PlayerPosition|string|null $position
 */
class TeamPlayer extends Model
{
    protected $fillable = [
        'team_id',
        'player_id',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'position' => PlayerPosition::class,
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
