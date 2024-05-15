<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Notification
 * @package App
 *
 * Properties
 * @property $id
 * @property $user_id
 * @property $message
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $user
 * @property $notifications
 *
 * @mixin Builder
 */
class Event extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this -> belongsTo(User::class);
    }

    public function notifications(): HasMany
    {
        return $this -> hasMany(Notification::class);
    }
}
