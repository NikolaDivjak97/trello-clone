<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Comment
 * @package App
 *
 * Properties
 * @property $id
 * @property $user_id
 * @property $card_id
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $user
 * @property $card
 *
 * @mixin Builder
 */
class Comment extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this -> belongsTo(User::class);
    }

    public function card(): BelongsTo
    {
        return $this -> belongsTo(Card::class);
    }
}
