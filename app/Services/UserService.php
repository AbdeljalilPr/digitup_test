<?php
namespace App\Services;

use App\DTOs\CreateUserDTO;
use App\DTOs\UpdateUserDTO;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;

class UserService {

    protected UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getAll() {
        return $this->userRepo->all();
    }

    public function getById(int $id): ?User {
        return $this->userRepo->find($id);
    }

    public function createUser(CreateUserDTO $dto): User {
        return $this->userRepo->create($dto);
    }

    public function updateUser(User $user, UpdateUserDTO $dto): User {
        return $this->userRepo->update($user, $dto);
    }

    public function deleteUser(User $user): void {
        $this->userRepo->delete($user);
    }
}
