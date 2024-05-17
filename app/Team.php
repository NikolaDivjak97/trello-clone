<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class User
 * @package App
 *
 * Properties
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $users
 *
 * @mixin Builder
 */
class Team extends Model
{
    protected $guarded = ['id'];

    public function users(): HasMany
    {
        return $this -> hasMany(User::class);
    }
}
