<?php

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function getAllUsers(): Collection;
    public function createUser(array $data): User;
    public function findUser(int $id): ?User;
    public function updateUser(int $id, array $data): bool;
    public function deleteUser(int $id): bool;
    public function searchUsers(array $filters);
}
