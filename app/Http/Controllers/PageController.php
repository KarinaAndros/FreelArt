<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function genres()
    {
        return view('genres.index');
    }

    public function create()
    {
        return view('users.registration');
    }

    public function login_user()
    {
        return view('users.login');
    }

}
