<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Repository
{
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Get user by email
     * @param string $email
     * @return Model|null
     */
    public function getUserByEmail(string $email): ?Model
    {
        return $this
            ->model
            ->newQuery()
            ->where('email', $email)
            ->first();
    }
}
