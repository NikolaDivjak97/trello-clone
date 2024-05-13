<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
