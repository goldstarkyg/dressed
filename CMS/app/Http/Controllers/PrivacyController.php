<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Redirect;

class PrivacyController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function privacy(){
        return view('pages.privacy');
    }
    
    public function applinks(){
        return view('pages.applinks');
    }

}
