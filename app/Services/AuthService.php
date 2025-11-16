<?php
namespace App\Services;

use App\Models\User;
use App\DTOs\CreateUserDTO;
use App\DTOs\LoginUserDTO;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(CreateUserDTO $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
        ]);
    }

    public function login(LoginUserDTO $dto): ?string
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            return null;
        }

        return $user->createToken('auth_token')->plainTextToken;
    }
}
