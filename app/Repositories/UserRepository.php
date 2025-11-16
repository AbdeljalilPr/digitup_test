
<?php
namespace App\Repositories;

use App\DTOs\CreateUserDTO;
use App\DTOs\UpdateUserDTO;
use App\Models\User;

class UserRepository implements UserRepositoryInterface {

    public function all() {
        return User::all();
    }

    public function find(int $id): ?User {
        return User::find($id);
    }

    public function create(CreateUserDTO $dto): User {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
            'role' => $dto->role,
        ]);
    }

    public function update(User $user, UpdateUserDTO $dto): User {
        $user->update([
            'name' => $dto->name ?? $user->name,
            'email' => $dto->email ?? $user->email,
            'role' => $dto->role ?? $user->role,
        ]);
        return $user;
    }

    public function delete(User $user): void {
        $user->delete();
    }
}
