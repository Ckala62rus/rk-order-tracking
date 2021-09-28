<?php

namespace App\Repositories;

use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Builder;

class TelegramUserRepository extends Repository
{
    /**
     * TelegramUserRepository constructor.
     */
    public function __construct()
    {
        $this->model = new TelegramUser();
    }

    /**
     * Create new query
     * @return Builder
     */
    public function query(): Builder
    {
        return $this
            ->model
            ->newQuery();
    }

    /**
     * Get telegram user by id
     * @param Builder $query
     * @param int $user_id
     * @return Builder
     */
    public function whereTelegramUserId(Builder $query, int $user_id): Builder
    {
        return $query
            ->where('telegram_user_id', $user_id);
    }
}
