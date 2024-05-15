<?php

namespace App\Http\Controllers;

use App\Team;
use App\User;


class HomeController extends Controller
{
    public function index()
    {
        $users = User::all();
        $teams = Team::all();

        return view('home', compact('users', 'teams'));
    }

    public function dashboard()
    {
        $users = User::all(['id', 'name']);

        return view('dashboard.index', compact('users'));
    }
}
