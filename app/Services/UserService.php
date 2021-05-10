<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @var UserRepository
     */
    public UserRepository $userRepository;

    /**
     * UserService constructor.
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users from Users table
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getUsers(int $limit): LengthAwarePaginator
    {
        return $this
            ->userRepository
            ->paginateAll($limit);
    }

    /**
     * Return one user by id
     * @param int $id
     * @return Model|null
     */
    public function getOneUser(int $id): ?Model
    {
        return $this
            ->userRepository
            ->getRecord($id);
    }

    /**
     * Create user
     * @param array $data
     * @return Model
     */
    public function createUser(array $data): Model
    {
        return $this
            ->userRepository
            ->store($this->userDataAdapter($data));
    }

    /**
     * Update one user by id
     * @param array $data
     * @param int $id
     * @return Model
     */
    public function updateUser(array $data, int $id): Model
    {
        return $this
            ->userRepository
            ->update($this->userDataAdapter($data), $id);
    }

    /**
     * Delete user by id
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        return $this
            ->userRepository
            ->destroy($id);
    }

    /**
     * Data adapter for user create and update user
     * @param array $data
     * @return array
     */
    public function userDataAdapter(array $data): array
    {
        if (isset($data["password"])) {
            $data["password"] = Hash::make($data["password"]);
        }

        return $data;
    }
}
