<?php

namespace App\Http\Controllers\Lk;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $user = Auth::user();

        $orders = [];
        $companyName = 'АО "Егорьевск-обувь"';

//        $date = Orders::where('AccountNum', '3797')
        $date = Orders::where('AccountNum', $user->account_id)
//                ->orderByRaw('OrderDate DESC')
                ->get();

        foreach ($date as $item) {
            $item->OrderQTY = round($item->OrderQTY, 2);
            $item->OrderConfirmQTY = round($item->OrderConfirmQTY, 2);
            $item->ProdOrderQTY = round($item->ProdOrderQTY, 2);
            $item->DeliveryDate = Carbon::parse($item->DeliveryDate)->format('d-m-Y');
            $item->EndDate = $item->EndDate ? Carbon::parse($item->EndDate)->format('d-m-Y') : "";
            $item->OrderDate = $item->OrderDate ? Carbon::parse($item->OrderDate)->format('d-m-Y') : "";
            $item->OrderQTY = number_format($item->OrderQTY, 0, ',', ' ');
            $item->ProdOrderQTY = number_format($item->ProdOrderQTY, 0, ',', ' ');
            $orders = $date;
        }

        return view('lk.index', compact('orders', 'companyName'));
    }

    /**
     * Testing vue table 2
     */
    public function vueOrders()
    {
        $user = Auth::user();

        if ($user->is_admin == true || $user->is_manager == true) {
            return view('lk.zip-order-table');
//            return view('lk.manager.manager-table');
//            return response(['Access is denied'], JsonResponse::HTTP_FORBIDDEN);
        }
        return view('lk.client.client-zip-table');
    }

    /**
     * return client zip order table
     * @return Application|Factory|View
     */
    public function clientZipTable()
    {
        $user = Auth::user();

        if ($user->is_admin == true || $user->is_manager == true) {
            return view('lk.manager.manager-table');
        }
        return view('lk.client.client-zip-table');
    }

    /**
     * Manager table
     * @return Application|ResponseFactory|Factory|View|Response
     */
    public function managerOrderTable()
    {
        $user = Auth::user();
        if ($user->is_admin == false && $user->is_manager == false) {
            return response(['Access is denied'], JsonResponse::HTTP_FORBIDDEN);
        }
        return view('lk.manager.manager-table');
    }

    /**
     * Return zip order table from client
     * @return Application|ResponseFactory|Factory|View|Response
     */
    public function zipOrderTable()
    {
        $user = Auth::user();
//        if ($user->is_admin == false) {
//            return response(['Access is denied'], JsonResponse::HTTP_FORBIDDEN);
//        }
        return view('lk.zip-order-table');
    }

    /**
     * Return zip detail order
     * @param int $id
     * @return Application|Factory|View
     */
    public function zipOrderDetail(int $id)
    {
        return view('lk.zip-order-detail', ["id"=>$id]);
    }

    /**
     * Client Dashboard
     * @return Application|Factory|View
     */
    public function template()
    {
        return view('lk.dashboard');
    }

}
