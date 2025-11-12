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
use App\Charts\DeviceChart;



class ChartController extends Controller
{

    public function showSalesChart()
    {
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $salesData = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            ])
            ->pluck('total', 'date');

        $labels = [];
        $data = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d-m');

            if (isset($salesData[$dateString])) {
                $data[] = $salesData[$dateString];
            } else {
                $data[] = 0;
            }
            $currentDate->addDay();
        }


        $categoryRevenue = Category::withTotalRevenue() // <-- Phép thuật ở đây
            ->orderBy('total_revenue', 'DESC')
            ->get();

        // Tách dữ liệu (giữ nguyên)
        $categoryLabels = $categoryRevenue->pluck('category_name');
        $categoryData = $categoryRevenue->pluck('total_revenue');
        return view('layouts.charts', compact(
            'labels',
            'data',
            'categoryLabels',
            'categoryData'
        ));
    }

    public function index(
        RevenueChart $revenueChart,
        TopProductChart $topProductChart,
        CategoryChart $categoryChart,
        UserGrowthChart $userGrowthChart,
        OrderStatusChart $orderStatusChart,
        DeviceChart $deviceChart
    ) {
        return view('layouts.statistics', [
            'revenueChart' => $revenueChart->build(),
            'topProductChart' => $topProductChart->build(),
            'categoryChart' => $categoryChart->build(),
            'userGrowthChart' => $userGrowthChart->build(),
            'orderStatusChart' => $orderStatusChart->build(),
            'deviceChart' => $deviceChart->build(),
        ]);
    }
}