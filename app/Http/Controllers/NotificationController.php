<?php

namespace App\Http\Controllers;

use App\Card;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function viewed(): JsonResponse
    {
        $user = auth()->user();

        if(!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not found!']);
        }

        $user -> notifications() -> update(['is_seen' => true]);

        return new JsonResponse(['success' => true, 'message' => 'Notifications viewed!']);
    }

    public function newNotifications(): JsonResponse
    {
        $user = auth()->user();

        if(!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not found!']);
        }

        $newNotifications = $user
            -> notifications()
            -> where('is_seen', false)
            -> orderBy('created_at', 'desc')
            -> get()
            -> toArray();

        return new JsonResponse(['success' => true, 'data' => $newNotifications]);
    }
}
