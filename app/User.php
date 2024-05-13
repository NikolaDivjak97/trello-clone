<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App
 *
 * Properties
 * @property $id
 * @property $icon
 * @property $is_admin
 * @property $name
 * @property $email
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $myBoard
 * @property $boards
 * @property $cards
 * @property $notifications
 *
 * @mixin Builder
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'is_admin', 'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // RELATIONS
    public function myBoards(): HasMany
    {
        return $this -> hasMany(Board::class);
    }

    public function boards(): BelongsToMany
    {
        return $this -> belongsToMany(Board::class);
    }

    public function cards(): BelongsToMany
    {
     return $this -> belongsToMany(Card::class);
    }

    public function notifications(): HasMany
    {
        return $this -> hasMany(Notification::class);
    }

    // GETTERS
    public function getInitialsAttribute()
    {
        $nameArr = explode(' ', $this -> name);
        $initials = '';

        foreach ($nameArr as $chunk)
        {
            $initials .= strtoupper($chunk[0]);
        }

        return $initials;
    }

    public function getIconAttribute($value)
    {
        if(!$value)
        {
            return '';
            return asset('images/default.png');
        }

        return asset('storage/users/' . $value);
    }
}
