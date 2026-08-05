<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $team_template_id
 * @property int $player_id
 */
class TeamTemplatePlayer extends Model
{
    protected $fillable = [
        'team_template_id',
        'player_id',
    ];

    public function teamTemplate(): BelongsTo
    {
        return $this->belongsTo(TeamTemplate::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
