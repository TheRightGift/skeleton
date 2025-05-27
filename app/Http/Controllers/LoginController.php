<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            $request->session()->regenerate();

            // Create API token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'remember' => $request->boolean('remember')
            ], 200);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

     public function showLogin()
    {
        return view('auth.login');
    }

}
