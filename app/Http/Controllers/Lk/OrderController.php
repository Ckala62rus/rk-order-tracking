<?php

namespace App\Http\Controllers\Lk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $orders = DB::connection('sqlsrv')->select('SELECT * FROM CustomersOrders_Test_RK1');
        //dd($models);
        return view('lk.index', compact('orders'));
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
