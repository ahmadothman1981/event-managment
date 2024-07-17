<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request -> validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user= \App\Models\User::where('email', $request->email)->first();
        if(!$user)
        {
            throw ValidationException::withMessages([
                'email' => ['The Provided Credintials are not correct']
            ]);
        }
        if(!Hash::check($request->password, $user->password))
        {
            throw ValidationException::withMessages([
                'password' => ['The Provided Credintials are not correct']
            ]);
        }
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {

    }
}