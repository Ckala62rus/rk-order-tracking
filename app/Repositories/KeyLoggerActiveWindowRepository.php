<?php

namespace App\Repositories;

use App\Models\KeyLoggerActiveWindow;

class KeyLoggerActiveWindowRepository extends Repository
{
    public function __construct()
    {
        $this->model = new KeyLoggerActiveWindow();
    }
}
