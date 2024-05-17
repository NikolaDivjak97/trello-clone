<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TeamController extends Controller
{
    public function index()
    {
        return view('dashboard.teams.index');
    }

    public function store(StoreTeamRequest $request)
    {
        $team = (new Team()) -> create([
            'name' => $request -> team_name,
            'description' => $request -> team_description
        ]);

        (new User()) -> whereIn('id', $request -> team_members) -> update(['team_id' => $team -> id]);

        return back();
    }

    public function table(Request $request)
    {
        $teams = (new Team()) -> query();

        return (new DataTables)->eloquent($teams)
            -> addColumn('users', function($team) {
                $html = '';

                foreach($team -> users as $user) {
                    $html .= '<span class="badge badge-primary p-1 m-1">' . $user -> name . '</span>';
                }

                return $html ?: 'None';
            })
            -> editColumn('created_at', function($team) {
                return $team -> created_at -> format('d.m.Y H:i');
            })
            -> rawColumns(['users'])
            -> toJson();
    }
}
