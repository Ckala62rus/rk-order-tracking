<?php

namespace App\Services;

use App\Repositories\LoginKeyLoggerRepository;
use Illuminate\Database\Eloquent\Collection;

class LoginKeyLoggerService
{
    protected LoginKeyLoggerRepository $keyLoggerRepository;

    public function __construct(LoginKeyLoggerRepository $keyLoggerRepository)
    {
        $this->keyLoggerRepository = $keyLoggerRepository;
    }

    /**
     * Get all logins
     * @return Collection
     */
    public function getAllLogin(): Collection
    {
        $query = $this
            ->keyLoggerRepository
            ->query();

        return $query->get();
    }

    public function getUserById(int $id)
    {
        return $this
            ->keyLoggerRepository
            ->getRecord($id);
    }
}
