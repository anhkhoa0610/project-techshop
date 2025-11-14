<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
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
        return view('layouts.statistics', [
            'revenueChart' => $revenueChart->build(),
            'topProductChart' => $topProductChart->build(),
            'categoryChart' => $categoryChart->build(),
            'userGrowthChart' => $userGrowthChart->build(),
            'orderStatusChart' => $orderStatusChart->build(),
            'revenueByPaymentMethodChart' => $revenueByPaymentMethodChart->build(),
        ]);
    }
}