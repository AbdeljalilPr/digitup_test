<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Services\AuthService;
use App\DTOs\CreateUserDTO;
use App\DTOs\LoginUserDTO;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\DTOs\CreateUserDTO;
use App\DTOs\LoginUserDTO;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => [ 'required','string','max:255'],
            'email' => ['required','email','unique:users'],
            'password' =>  ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(['admin','formateur','apprenant','entreprise'])],
        ]);

        $dto = new CreateUserDTO($data['name'], $data['email'], $data['password'], $data['role']);

        $user = $this->authService->register($dto);

        return response()->json(['message' => 'User successfully registered', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' =>['required','string'] ,
        ]);

        $dto = new LoginUserDTO($data['email'], $data['password']);

        $token = $this->authService->login($dto);

        if (!$token) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => auth()->user()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
