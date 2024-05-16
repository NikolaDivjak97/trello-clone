<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Board
 * @package App
 *
 * Properties
 * @property $id
 * @property $user_id
 * @property $name
 * @property $description
 * @property $background
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $owner
 * @property $users
 * @property $phases
 * @property $card
 *
 * @mixin Builder
 */
class Board extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::deleting(function ($board) {
            $board -> users() -> sync([]);
            $board -> labels() -> delete();
            $board->phases->each(function ($phase) {
                $phase->delete();
            });
        });
    }

    public function owner(): BelongsTo
    {
        return $this -> belongsTo(User::class, 'user_id');
    }

    public function users(): BelongsToMany
    {
        return $this -> belongsToMany(User::class);
    }

    public function phases(): HasMany
    {
        return $this -> hasMany(Phase::class);
    }

    public function labels(): HasMany
    {
        return $this -> hasMany(Label::class);
    }

    // FUNCTIONS
    public function hasUser(User $user): bool
    {
        if($this -> users() -> where('users.id', $user -> id) -> exists()) return true;

        return false;
    }
}
