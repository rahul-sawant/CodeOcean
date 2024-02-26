<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        auth()->login($user);

        return redirect('dashboard');
    }

    private function poolCalc()
    {
        //Get the user where pool = 1
        $poolUser = Pools::where('pool_level', 'pool1')->first();
        //Check if the pool user is the first user to register
        $users = User::first();
        if($poolUser->user_id == $users->id){
            //If the pool user is the first user to register, needs 14 users to complete the pool
            Users::with('pools')->where('pool_level', 'pool1')->count() >= 14;
            //Promote Pool User
            $poolUser->update(['pool_level' => 'pool2']);
        }
        else
        {
            //If the pool user is not the first user to register, needs 8 users to complete the pool
            Users::with('pools')->where('pool_level', 'pool1')->count() >= 8;
            //Promote Pool User
            $poolUser->update(['pool_level' => 'pool2']);
        }

    }
}
