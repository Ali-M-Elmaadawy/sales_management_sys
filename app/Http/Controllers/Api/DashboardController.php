<?php 
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
class DashboardController extends Controller
{
    public function stats()
    {
        $users = User::count();
        $products = Product::count();

        $orders = Order::count();

        $revenue = Order::sum('total'); 


        $today_total = Order::whereDate('created_at', today())->sum('total');

        $today_sold_products = OrderItem::whereDate('created_at', today())->sum('qty');

        $todayOrders = Order::whereDate('created_at', today())->count();
        

        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->sum('total');



        return response()->json([
            'users' => $users,
            'products' => $products,
            'today_sold_products' => $today_sold_products,
            'orders' => $orders,
            'revenue' => $revenue,
            'today_total' => $today_total,
            'today_orders' => $todayOrders,
            'monthly_revenue' => $monthlyRevenue,
        ]);
    }
}