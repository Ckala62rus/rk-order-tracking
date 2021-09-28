<?php

namespace App\Repositories;

use App\Models\TelegramLogs;

class TelegramLogsRepository extends Repository
{
    /**
     * TelegramLogsRepository constructor.
     */
    public function __construct()
    {
        $this->model = new TelegramLogs();
    }
}
