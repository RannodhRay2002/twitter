<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Add this line
use Illuminate\Support\Facades\Hash; // Add this line

class AuthController extends Controller
{
    public function register(){
        return view('auth.register');
    }

    public function store(){
        //validate
        //create the user
        //redirect
        $validated = request()->validate([
            'name'=>'required|min:3|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|confirmed|min:8',
        ]);

        $user = User::create(
            [
                'name'=>$validated['name'],
                'email'=>$validated['email'],
                'password'=>Hash::make($validated['password']),
            ]
        );
        return redirect()->route('dashboard')->with('success','Your account has been created successfully!');
    }

    public function login(){
        return view('auth.login');
    }

    public function authenticate(){
       
        

        $validated = request()->validate([
            'email'=>'required|email',
            'password'=>'required|min:8',
        ]);

        if(auth()->attempt($validated)){

            request()->session()->regenerate();

            return redirect()->route('dashboard')->with('success','Logged in successfully!');
        }

        return redirect()->route('login')->withErrors([
            'email'=>'Your provided credentials could not be verified.'
        ]);
    }
    public function logout(){
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()->route('dashboard')->with('success','Logged out successfully!');
    }
}
