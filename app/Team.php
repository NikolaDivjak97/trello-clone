<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $guarded = ['id'];

    public function users(): HasMany
    {
        return $this -> hasMany(User::class);
    }
}
