<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Card;
use App\Comment;
use App\Image;
use App\User;
use App\Notification;
use Carbon\Carbon;
use PHPUnit\Util\Json;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CardRequest;

class CardController extends Controller
{
    public function store(CardRequest $request): JsonResponse
    {
        $card = Card::create($request->only(['phase_id', 'name', 'board_id', 'user_id', 'description', 'difficulty']));

        $card -> users() -> sync($request->members ?? []);
        $card -> labels() -> sync($request->labels ?? []);

        $eventMessage = 'User ' . $card -> owner -> name . ' created card: ' . $card -> name;

        Notification::notifyAll($card -> owner, $card, $card -> users, 'created', $eventMessage);

        return new JsonResponse(['message' => 'Card created successfully!']);
    }

    public function getCardData(Request $request): JsonResponse {
        $card = Card::find($request->card_id);

        if(!$card) {
            return new JsonResponse(['success' => false, 'message' => 'Card not found!']);
        }

        $html = view('modals.task-modal', compact('card'))->render();

        return new JsonResponse(['success' => true, 'html' => $html]);
    }

    public function updatePhase(Request $request): JsonResponse {
        if(!$request -> card_id || !$request -> phase_id) {
            return new JsonResponse(['success' => false, 'message' => 'Missing required parameters!']);
        }

        $card = Card::find($request -> card_id);

        if(!$card) {
            return new JsonResponse(['success' => false, 'message' => 'Card not found!']);
        }

//        if($card -> phase_id == $request -> phase_id) {
//            return new JsonResponse(['success' => false, 'message' => 'New and old phases are same!']);
//        }

        $card -> phase_id = $request -> phase_id;
        $card -> order = $request -> order;

        $card -> save();

        $eventMessage = 'User ' . auth()->user()->name . ' moved card ' . $card -> name .' to list' . $card -> phase -> name;

        Notification::notifyAll(auth()->user(), $card, $card -> users, 'update_phase', $eventMessage);

        return new JsonResponse(['success' => true, 'message' => 'Card phase updated successfully!']);
    }

    public function updateCardMembers(Request $request): JsonResponse
    {
        if(!$request -> card_id || !$request -> members) {
            return new JsonResponse(['success' => false, 'message' => 'Missing required parameters!']);
        }

        $card = Card::find($request -> card_id);

        if(!$card) {
            return new JsonResponse(['success' => false, 'message' => 'Card not found!']);
        }

        if(isset($request -> members))
        {
            foreach($request -> members as $member)
            {
                if($card -> users()->where('user_id', $member)->exists()) continue;

                $card -> users() -> attach($member);
            }
        }

        $usersToNotify = (new User()) -> whereIn('id', $request -> members)->get();
        $eventMessage = 'User ' . auth() -> user()->name . ' invited new members to card: ' . $card -> name;

        Notification::notifyAll(auth()->user(), $card, $usersToNotify, 'update_members', $eventMessage);

        return new JsonResponse(['success' => true, 'message' => 'Card members updated successfully!']);
    }

    public function addComment(Request $request): JsonResponse
    {
        if(!$request -> card_id || !$request -> comment) {
            return new JsonResponse(['success' => false, 'message' => 'Missing required fields!']);
        }

        $card = (new Card()) -> find($request -> card_id);

        if(!$card) {
            return new JsonResponse(['success' => false, 'message' => 'Card not found!']);
        }

        // TODO: later comments will have images,attachments...
        $comment = (new Comment()) -> create([
            'card_id' => $card -> id,
            'user_id' => auth()->id(),
            'description' => $request -> comment
        ]);

        $eventMessage = 'User ' . auth() -> user() -> name . ' commented on card: ' . $card -> name;

        Notification::notifyAll(auth() -> user(), $comment, $card -> users, 'created', $eventMessage);

        return new JsonResponse(['success' => true, 'message' => 'You commented successfully!']);
    }

    public function addNewLabel(Request $request): JsonResponse
    {
        if(!$request -> card_id || !$request -> label_id) {
            return new JsonResponse(['success' => false, 'message' => 'Missing required fields!']);
        }

        $card = (new Card()) -> find($request -> card_id);

        if(!$card) {
            return new JsonResponse(['success' => false, 'message' => 'Card not found!']);
        }

        $card -> labels() -> attach($request -> label_id);

        return new JsonResponse(['success' => true, 'message' => 'Label added successfully!']);
    }

    public function addDueDate(Request $request): JsonResponse
    {
        if(!$request -> card_id || !$request -> due_date) {
            return new JsonResponse(['success' => false, 'message' => 'Missing required fields!']);
        }

        $card = (new Card()) -> find($request -> card_id);

        if(!$card) {
            return new JsonResponse(['success' => false, 'message' => 'Card not found!']);
        }

        $card -> due_date = Carbon::create($request -> due_date);
        $card -> save();

        return new JsonResponse(['success' => true, 'message' => 'Due date updated successfully!']);
    }

    public function addImages(Request $request)
    {
        if(!$request -> card_id || !$request -> images) {
            return new JsonResponse(['success' => false, 'message' => 'Missing required fields!']);
        }

        $card = (new Card()) -> find($request -> card_id);

        if(!$card) {
            return new JsonResponse(['success' => false, 'message' => 'Card not found!']);
        }

        foreach ($request -> images as $image)
        {
            $image -> store('public/images');

            (new Image()) -> create([
                'imageable_id' => $card -> id,
                'imageable_type' => Card::class,
                'path' => $image -> hashName()
            ]);
        }

        $eventMessage = 'User ' . auth() -> user() -> name  . ' added new images to card:' . $card -> name;

        Notification::notifyAll(auth() -> user(), $card, $card -> users, 'new_images', $eventMessage);

        return new JsonResponse(['success' => true, 'message' => 'Images added successfully!']);
    }

    public function addAttachment(Request $request)
    {
        if(!$request -> card_id || !$request -> attachment) {
            return new JsonResponse(['success' => false, 'message' => 'Missing required fields!']);
        }

        $card = (new Card()) -> find($request -> card_id);

        if(!$card) {
            return new JsonResponse(['success' => false, 'message' => 'Card not found!']);
        }

        $attachment = $request -> attachment;

        $attachment -> store('public/attachments');

        (new Attachment()) -> create([
            'attachable_id' => $card -> id,
            'attachable_type' => Card::class,
            'path' => $attachment -> hashName()
        ]);

        $eventMessage = 'User ' . auth() -> user() -> name . ' added new attachment to card:' . $card -> name;


        Notification::notifyAll(auth() -> user(), $card, $card -> users, 'new_attachment', $eventMessage);

        return new JsonResponse(['success' => true, 'message' => 'Attachment added successfully!']);
    }
}
