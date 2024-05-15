<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EventController extends Controller
{
    public function table(Request $request)
    {
        $events = (new Event()) -> query();


        $events -> orderBy('created_at', 'desc');

        return (new DataTables)->eloquent($events)

            -> editColumn('user_id', function($event) {
                return $event -> user -> name;
            })
            -> editColumn('created_at', function($event) {
                return $event -> created_at -> format('d.m.Y H:i');
            })
            -> toJson();

    }
}
