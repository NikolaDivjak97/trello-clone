<?php

namespace App\Http\Controllers;

use App\Card;
use App\Notification;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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

    public function table(Request $request)
    {
        $notifications = (new Notification()) -> query();

        if(isset($request -> notification_type)) {
            $notifications = $notifications -> where('notifiable_type', $request -> notification_type);
        }

        if(isset($request -> owner)) {
            $notifications = $notifications -> where('owner_id', $request -> owner);
        }
        if(isset($request -> event)) {
            $notifications = $notifications -> where('event', $request -> event);
        }

        $notifications -> orderBy('created_at', 'desc');

        return (new DataTables)->eloquent($notifications)
            -> editColumn('owner_id', function($notification) {
                return $notification -> owner -> name;
            })
            -> editColumn('user_id', function($notification) {
                return $notification -> user -> name;
            })
            -> editColumn('link', function($notification) {
                return '<a href="'. $notification -> link . '" class="btn btn-sm btn-primary" target="_blank">Open</a>';
            })
            -> editColumn('created_at', function($notification) {
                return $notification -> created_at -> format('d.m.Y H:i');
            })
            -> rawColumns(['link'])
            -> toJson();

    }
}
