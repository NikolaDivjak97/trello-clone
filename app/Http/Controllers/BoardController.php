<?php

namespace App\Http\Controllers;

use App\Team;
use App\User;
use App\Board;
use App\Image;
use App\Notification;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function store(Request $request)
    {
        //  TODO: Make Custom Request?
        $validated = $request -> validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'members' => 'nullable|array',
            'members.*' => 'required|integer|exists:users,id',
            'background' => 'nullable|file',
            'teams' => 'nullable|array',
            'teams.*' => 'required|exists:teams,id'
        ]);

        $board = (new Board()) -> create([
            'user_id' => auth() -> id(),
            'name'=> $validated['name'],
            'description' => $validated['description'] ?? '',
            'background' => ''
        ]);

        if(isset($validated['background']))
        {
            $image = $validated['background'];
            $image -> store('public/boards');

            (new Image()) -> create([
                'imageable_id' => $board -> id,
                'imageable_type' => Board::class,
                'path' => $image -> hashName()
            ]);

            $board -> background = $image -> hashName();
            $board -> save();
        }

        $boardMembers = $validated['members'] ?? [];

        if(isset($validated['teams']))
        {
            foreach ($validated['teams'] as $teamId)
            {
                $team = (new Team()) -> find($teamId);
                $userIDs = $team -> users() -> pluck('users.id') -> toArray();
                $boardMembers = array_merge($boardMembers, $userIDs);
            }
        }

        $board -> users() -> sync(array_unique($boardMembers));

        $eventMessage = 'User ' . $board -> owner -> name . ' created board ' . $board -> name;

        Notification::notifyAll($board -> owner, $board, $board -> users, 'created', $eventMessage);

        return redirect('/')->with('success');
    }

    public function show(Board $board)
    {
        $user = auth() -> user();

        if(!$board->whereHas('users', function($query) use ($user) {
            $query -> where('user_id', $user -> id);
        }) -> count()) {
            abort('404');
        }

        $users = $board->users()->get();

        return view('boards.show', compact('board', 'users'));
    }

    public function syncUsers(Request $request)
    {
        $validated = $request -> validate([
            'board_id' => 'required|integer|exists:boards,id',
            'selectMembersBoard' => 'nullable|array',
            'selectMembersBoard.*' => 'required|integer|exists:users,id',
        ]);

        $board = Board::find($request->board_id);

        if(isset($validated['selectMembersBoard']))
        {
            $board -> users() -> sync($validated['selectMembersBoard']);
        }

        $usersToNotify = (new User()) -> whereIn('id', $validated['selectMembersBoard'])->get();
        $eventMessage = 'User ' . $board -> owner -> name . ' invited new users to board' . $board -> name;

        Notification::notifyAll($board -> owner, $board, $usersToNotify, 'created', $eventMessage);

        return redirect('/boards/' . $board->id);
    }

    public function destroy(Board $board)
    {
        $user = auth() -> user();
        $eventMessage = 'User ' . $user -> name . ' deleted the board: ' . $board -> name;

        Notification::notifyAll($user, $board, $board -> users, 'board_delete', $eventMessage);

        $board -> delete();

        return redirect('/')->with(['success' => 'You deleted the board ' . $board -> name]);
    }

    public function leaveBoard(Board $board)
    {
        $user = auth()-> user();

        $board -> users() -> detach($user -> id);
        $eventMessage = 'User ' . $user -> name . ' left the board: ' . $board -> name;

        Notification::notifyAll($user, $board, [$board -> owner], 'board_leave', $eventMessage);

        return redirect('/')->with(['success' => 'You left the board ' . $board -> name]);
    }
}
