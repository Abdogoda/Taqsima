<?php

namespace App\Models;

use Database\Factories\AssistFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $goal_id
 * @property int $player_id
 * @property Carbon $created_at
 */
class Assist extends Model
{
    /** @use HasFactory<AssistFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'goal_id',
        'player_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
