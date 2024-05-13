<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Team;
use App\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function store(StoreTeamRequest $request)
    {
        $team = (new Team()) -> create([
            'name' => $request -> team_name,
            'description' => $request -> team_description
        ]);

        (new User()) -> whereIn('id', $request -> team_members) -> update(['team_id' => $team -> id]);

        return back();
    }
}
