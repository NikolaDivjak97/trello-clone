<?php

namespace App;

use App\Events\NewNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Notification
 * @package App
 *
 * Properties
 * @property $id
 * @property $owner_id
 * @property $user_id
 * @property $is_seen
 * @property $notifiable_id
 * @property $notifiable_type
 * @property $link
 * @property $message
 * @property $created_at
 * @property $updated_at
 *
 * Relation Properties
 * @property $owner
 * @property $user
 * @property $notifiable
 *
 * @mixin Builder
 */
class Notification extends Model
{
    protected $guarded = ['id'];

    public function owner(): BelongsTo
    {
        return $this -> belongsTo(User::class, 'owner_id');
    }

    public function user(): BelongsTo
    {
        return $this -> belongsTo(User::class);
    }
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    // FUNCTIONS
    public static function notifyAll($owner, $model, $users, $event, $eventMessage)
    {
        $eventModel = (new Event()) -> create([
            'user_id' => $owner -> id,
            'message' => $eventMessage ?? 'Unknown'
        ]);

        foreach ($users as $user) {
            if ($user->id == $owner->id) continue; // don't notify yourself

            $notification = (new Notification())->create([
                'event_id' => $eventModel -> id,
                'owner_id' => $owner -> id,
                'user_id' => $user->id,
                'is_seen' => false,
                'notifiable_id' => 0,
                'notifiable_type' => '',
                'link' => '',
                'message' => '',
                'event' => $event
            ]);

            switch (get_class($model)) {
                case Board::class:
                    $notification->notifiable_id = $model->id;
                    $notification->notifiable_type = Board::class;

                    switch ($event) {
                        case 'created':
                            $notification->link = route('boards.show', $model->id);
                            $notification->message = 'User ' . $owner->name . ' has invited you to the board ' . $model->name;
                            $notification->save();

                            break;
                        default:
                            $notification->delete();
                    }

                    break;
                case Comment::class:
                    $notification->notifiable_id = $model->id;
                    $notification->notifiable_type = Comment::class;

                    switch ($event) {
                        case 'created':
                            $notification->link = route('boards.show', $model->card->board->id) . '?card=' . $model->card->id;
                            $notification->message = 'User ' . $owner->name . ' has added a comment on card ' . $model->card->name;
                            $notification->save();

                            break;
                        default:
                            $notification->delete();
                    }

                    break;
                case Card::class:
                    $notification->notifiable_id = $model->id;
                    $notification->notifiable_type = Card::class;

                    switch ($event) {
                        case 'created':
                            $notification->link = route('boards.show', $model->board->id) . '?card=' . $model->id;
                            $notification->message = 'User ' . $owner->name . ' has created card ' . $model->name . ' on board ' . $model->board->name;
                            $notification->save();

                            break;
                        case 'update_phase':
                            $notification->link = route('boards.show', $model->board->id) . '?card=' . $model->id;
                            $notification->message = 'User ' . $owner->name . ' has moved card ' . $model->name . ' to List ' . $model->phase->name . ' on board ' . $model->board->name;
                            $notification->save();

                            break;
                        case 'update_members':
                            $notification->link = route('boards.show', $model->board->id) . '?card=' . $model->id;
                            $notification->message = 'User ' . $owner->name . ' has added you to card ' . $model->name . ' on board ' . $model->board->name;
                            $notification->save();

                            break;
                        case 'new_attachment':
                            $notification->link = route('boards.show', $model->board->id) . '?card=' . $model->id;
                            $notification->message = 'User ' . $owner->name . ' added new attachment to card:  ' . $model->name;
                            $notification->save();

                            break;
                        case 'new_images':
                            $notification->link = route('boards.show', $model->board->id) . '?card=' . $model->id;
                            $notification->message = 'User ' . $owner->name . ' added new images to card:  ' . $model->name;
                            $notification->save();

                            break;
                        default:
                            $notification->delete();
                    }

                    break;
                default:
                    $notification->delete();
            }

            // check if notification still exists (it is not deleted for some reason)
            if($notification -> exists) {
                event(new NewNotification($notification -> user_id));
            }
        }
    }
}
