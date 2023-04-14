<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Auth;
use KChat\ActivityLog;

class AuthController extends Controller
{
	
    function signon(Request $request)
    {
        $request->validate([
            'first_name'         =>   'required',
            'last_name'         =>   'required',
            'email'        =>   'required|email|unique:users',
            'password'     =>   'required|min:6',
        ]);

        $data = $request->all();

        User::create([
            'first_name'  =>  $data['first_name'],
            'last_name'  =>  $data['last_name'],
            'email' =>  $data['email'],
            'phone' =>  $data['phone'],
            'password' => Hash::make($data['password']),
            'created_at' => now(),
        ]);
		
        return redirect('login')->with('success', 'Registration Completed');
    }
	
    function login(Request $request)
    {
        $request->validate([
            'email' =>  'required',
            'password'  =>  'required'
        ]);

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){
			ActivityLog::log()->save('Login','You have successfully logged in.');
		}
    }

    function logout(Request $request)
    {
		ActivityLog::log()->save('Logout','You have successfully logged out.');
		
        Session::flush();

        Auth::logout();
		
    }
}
