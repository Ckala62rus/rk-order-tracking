<?php

namespace App\Http\Controllers\Lk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Show client all detail for order
 * @package App\Http\Controllers\Lk
 */
class OrderController extends Controller
{
    /**
     * get all information for orders
     */
    public function index()
    {
//        $user = Auth::user();
//        dd($user);
//        dd('order information');
        return view('lk.index');
    }

    public function store(Request $request)
    {
//        dd('create order');
    }

    public function show($id)
    {
//        dd('show order by id = ' . $id);
    }

    public function update(Request $request, $id)
    {
//        dd('update order by id');
    }

    public function destroy($id)
    {
        //
    }
}
