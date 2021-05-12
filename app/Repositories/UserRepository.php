<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        $this->model = new User();
    }
}
