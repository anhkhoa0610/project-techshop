<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Charts\RevenueChart;
use App\Charts\TopProductChart;
use App\Charts\CategoryChart;
use App\Charts\UserGrowthChart;
use App\Charts\OrderStatusChart;
use App\Charts\RevenueByPaymentMethodChart;



class ChartController extends Controller
{
    public function index(
        RevenueChart $revenueChart,
        TopProductChart $topProductChart,
        CategoryChart $categoryChart,
        UserGrowthChart $userGrowthChart,
        OrderStatusChart $orderStatusChart,
        RevenueByPaymentMethodChart $revenueByPaymentMethodChart
    ) {
        // 1. User mới tạo gần nhất
        $newestUser = User::latest('created_at')->first();

        // 2. User mua hàng nhiều nhất (tính theo tổng số tiền)
        $topBuyerData = DB::table('users')
            ->join('orders', 'users.user_id', '=', 'orders.user_id')
            ->where('orders.status', '!=', 'cancelled')
            ->select('users.user_id', DB::raw('SUM(orders.total_price) as total_spent'))
            ->groupBy('users.user_id')
            ->orderByDesc('total_spent')
            ->first();

        $topBuyer = $topBuyerData ? User::find($topBuyerData->user_id) : null;
        if ($topBuyer && $topBuyerData) {
            $topBuyer->total_spent = $topBuyerData->total_spent;
        }

        // 3. Sản phẩm bán được gần nhất
        $recentlySoldProduct = Product::select('products.*', 'orders.order_date')
            ->join('order_details', 'products.product_id', '=', 'order_details.product_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->where('orders.status', '!=', 'cancelled')
            ->orderByDesc('orders.order_date')
            ->first();

        // 4. Doanh thu trong tuần (7 ngày gần nhất)
        $weeklyRevenue = Order::where('status', '!=', 'cancelled')
            ->where('order_date', '>=', Carbon::now()->subDays(7))
            ->sum('total_price');

        return view('layouts.statistics', [
            'revenueChart' => $revenueChart->build(),
            'topProductChart' => $topProductChart->build(),
            'categoryChart' => $categoryChart->build(),
            'userGrowthChart' => $userGrowthChart->build(),
            'orderStatusChart' => $orderStatusChart->build(),
            'revenueByPaymentMethodChart' => $revenueByPaymentMethodChart->build(),
            // Statistics data
            'newestUser' => $newestUser,
            'topBuyer' => $topBuyer,
            'recentlySoldProduct' => $recentlySoldProduct,
            'weeklyRevenue' => $weeklyRevenue,
        ]);
    }
}
