<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Label
 * @package App
 *
 * Properties
 * @property $id
 * @property $name
 * @property $color
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $board
 * @property $cards
 *
 * @mixin Builder
 */
class Label extends Model
{
    protected $guarded = ['id'];

    public function board(): BelongsTo
    {
        return $this -> belongsTo(Board::class);
    }

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class);
    }
}
