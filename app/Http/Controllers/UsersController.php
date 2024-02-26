<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        //Check if user is authenticated
        if(!auth()->check()){
            return redirect()->route('login');
        }
    }

}
