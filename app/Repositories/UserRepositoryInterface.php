<?php
namespace App\Repositories;

use App\DTOs\CreateUserDTO;
use App\DTOs\UpdateUserDTO;
use App\Models\User;

interface UserRepositoryInterface {
    public function all();
    public function find(int $id): ?User;
    public function create(CreateUserDTO $dto): User;
    public function update(User $user, UpdateUserDTO $dto): User;
    public function delete(User $user): void;
}
