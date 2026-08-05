<?php

namespace App\Models;

use App\Enums\SessionPlayerStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $session_id
 * @property int $player_id
 * @property SessionPlayerStatus|string $status
 */
class SessionPlayer extends Model
{
    protected $fillable = [
        'session_id',
        'player_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => SessionPlayerStatus::class,
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function scopePlaying(Builder $query): Builder
    {
        return $query->where('status', SessionPlayerStatus::Playing->value);
    }

    public function scopeBench(Builder $query): Builder
    {
        return $query->where('status', SessionPlayerStatus::Bench->value);
    }

    public function scopeInjured(Builder $query): Builder
    {
        return $query->where('status', SessionPlayerStatus::Injured->value);
    }

    public function scopeAbsent(Builder $query): Builder
    {
        return $query->where('status', SessionPlayerStatus::Absent->value);
    }
}
