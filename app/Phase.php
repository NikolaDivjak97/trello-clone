<?php

namespace App;

use App\Card;
use App\Board;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Phase
 * @package App
 *
 * Properties
 * @property $id
 * @property $board_id
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $board
 * @property $cards
 *
 * @mixin Builder
 */
class Phase extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::deleting(function ($phase) {
            $phase -> cards() -> each(function ($card) {
                $card->delete();
            });
        });
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
