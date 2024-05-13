<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Attachment
 * @package App
 *
 * Properties
 * @property $id
 * @property $attachable_id
 * @property $attachable_type
 * @property $path
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $attachable
 *
 * @mixin Builder
 */
class Attachment extends Model
{
    protected $guarded = ['id'];

    public function attachable(): MorphTo
    {
        return $this -> morphTo();
    }

    // GETTERS
    public function getFullPathAttribute()
    {
        return asset('storage/attachments/' . $this -> path);
    }
}
