<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('dashboard.users.index', compact('users'));
    }

    public function table(Request $request)
    {
        $users = (new User()) -> query();

        return (new DataTables)->eloquent($users)
            -> editColumn('team_id', function($user) {
                return $user -> is_admin ? 'Yes' : 'No';
            })
            -> editColumn('team_id', function($user) {
                return $user -> team ? $user -> team -> name : 'Not in a team';
            })
            -> editColumn('created_at', function($event) {
                return $event -> created_at -> format('d.m.Y H:i');
            })
            -> toJson();
    }

    public function profile()
    {
        $user = auth()->user();

        return view('users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request -> validate([
            'name' => 'required|string',
            'icon' => 'nullable|image',
            'password' => 'nullable|string|min:8',
            'confirm_password' => 'nullable|string|min:8',
        ]);

        $user = auth()->user();

        if($validated['password'] !== $validated['confirm_password']) {
            return redirect('/profile')->with('password_mismatch', 'Password mismatch.');
        }

        $user -> name = $validated['name'];

        if($validated['password']) {
            $user -> password = bcrypt($validated['password']);
        }

        if(isset($validated['icon'])) {
            $icon = $validated['icon'];

            $icon -> store('public/users');

            $user -> icon = $icon -> hashName();
        }

        $user -> save();

        return redirect('/profile')->with('success', 'Profile updated.');
    }
}
