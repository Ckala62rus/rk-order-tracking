<?php

namespace App\Http\Controllers;

use App\Models\Asterics;
use Illuminate\Http\Request;

class AstericsController extends Controller
{
    public function __invoke()
    {
        $users = Asterics::all();
        dd($users);
    }
}
