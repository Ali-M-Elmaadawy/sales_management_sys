<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\SaleService;
class OrderController extends Controller
{

    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');

        return $this->saleService->getSales($perPage, $search);
    }

    public function store(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',

            'items' => 'required|array|min:1'
        ]);

        return $this->saleService->createSale($request->all());
    }


    public function destroy(Order $order)
    {
        return $this->saleService->destroySale($order);
    }

    public function restore(Order $order)
    {
        return $this->saleService->restoreSale($order);
    }

}