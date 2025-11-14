<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    //register new user without any token before
    public function register(Request $request)
    {
        //check informatins
        $data = $request->validate([
            'name' => [ 'required','string','max:255'],
            'email' => ['required','email','unique:users'],
            'password' =>  ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(['admin','formateur','apprenant','entreprise'])],
        ]);

        //creat user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        return response()->json(['message' => 'User successfully registered', 'user' => $user], 201);
    }


    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' =>['required','string'] ,
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        //creat new token
            $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
