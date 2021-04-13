<?php

namespace App\Http\Controllers\Lk;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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

        $orders = [];
        $companyName = '';

        $date = Orders::where('ContractorNameActual', 'like', '%Егорьевск-обувь%')
            ->get();


        foreach($date as $item)
        {
            $item->OrderQTY = round($item->OrderQTY, 2);
            $item->OrderConfirmQTY = round($item->OrderConfirmQTY, 2);
            $item->ProdOrderQTY = round($item->ProdOrderQTY, 2);
            $orders = $date;
        }

//dd($orders);

        return view('lk.index', compact('orders', 'companyName'));
    }

    /**
     * Client Dashboard
     * @return Application|Factory|View
     */
    public function template()
    {
        return view('lk.dashboard');
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
