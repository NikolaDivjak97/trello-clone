<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Image
 * @package App
 *
 * Properties
 * @property $id
 * @property $imageable_id
 * @property $imageable_type
 * @property $path
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $imageable
 *
 * @mixin Builder
 */
class Image extends Model
{
    protected $guarded = ['id'];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    // GETTERS
    public function getFullPathAttribute()
    {
        switch ($this -> imageable_type) {
            case Card::class:
                return asset('storage/images/' . $this -> path);
            default:
                return $this -> path;
        }
    }
}
