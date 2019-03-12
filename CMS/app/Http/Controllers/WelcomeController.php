<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Redirect;

class WelcomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function welcome()
    {
        //return view('welcome');
        $user = Auth::user();
        if ($user->isAdmin()) {
            return view('pages.admin.home');

        }
        return Redirect::to('/login');
        //return view('pages.user.login');
    }
    
}
