<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\DTOs\CreateUserDTO;
use App\DTOs\UpdateUserDTO;
use App\Models\User;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return response()->json($this->userService->getAll());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|string|min:6',
            'role'=>'required|in:admin,formateur,apprenant,entreprise'
        ]);

        $dto = new CreateUserDTO($data['name'], $data['email'], $data['password'], $data['role']);
        $user = $this->userService->createUser($dto);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'=>'sometimes|string',
            'email'=>'sometimes|email|unique:users,email,'.$user->id,
            'role'=>'sometimes|in:admin,formateur,apprenant,entreprise'
        ]);

        $dto = new UpdateUserDTO(
            $data['name'] ?? null,
            $data['email'] ?? null,
            $data['role'] ?? null
        );

        $user = $this->userService->updateUser($user, $dto);

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        return response()->json(['message'=>'User deleted successfully']);
    }
}
