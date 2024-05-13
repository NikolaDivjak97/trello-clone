<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Card
 * @package App
 *
 * Properties
 * @property $id
 * @property $order
 * @property $phase_id
 * @property $board_id
 * @property $user_id
 * @property $name
 * @property $description
 * @property $due_date
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $user
 * @property $phase
 * @property $board
 * @property $comments
 * @property $images
 * @property $users
 *
 * @mixin Builder
 */
class Card extends Model
{
    protected $guarded = ['id'];
    protected $dates = ['due_date'];

    public function owner(): BelongsTo
    {
        return $this -> belongsTo(User::class, 'user_id');
    }

    public function phase(): BelongsTo
    {
        return $this -> belongsTo(Phase::class);
    }

    public function board(): BelongsTo
    {
        return $this -> belongsTo(Board::class);
    }

    public function comments(): HasMany
    {
        return $this -> hasMany(Comment::class);
    }

    public function users(): BelongsToMany
    {
        return $this -> belongsToMany(User::class);
    }

    public function images(): MorphMany
    {
        return $this -> morphMany(Image::class, 'imageable');
    }

    public function attachments(): MorphMany
    {
        return $this -> morphMany(Attachment::class, 'attachable');
    }

    public function labels(): BelongsToMany
    {
        return $this -> belongsToMany(Label::class);
    }

    // GETTERS
    public function getDifficultyAttribute($value)
    {
        switch($value) {
            case 1:
                return "<span class='badge badge-success p-1'>Easy</span>";
            case 2:
                return "<span class='badge badge-warning p-1'>Medium</span>";
            case 3:
                return "<span class='badge badge-danger p-1'>Hard</span>";
        }

        return $value;
    }

}
