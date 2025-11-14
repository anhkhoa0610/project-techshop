<?php

namespace App\Charts;

use App\Models\Order;      // Để lọc trạng thái đơn hàng
use App\Models\OrderDetail; // Để lấy chi tiết sản phẩm và số lượng/giá
use App\Models\Category;    // Để lấy tên danh mục
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class CategoryChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LarapexChart
    {
        
        $data = OrderDetail::select(
                'categories.category_name as category_name',
                DB::raw('SUM(order_details.quantity * order_details.unit_price) as total_revenue') 
            )
            ->join('products', 'order_details.product_id', '=', 'products.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->where('orders.status', 'completed') 
            ->groupBy('categories.category_name')
            ->orderByDesc('total_revenue')
            ->get();

        $labels = $data->pluck('category_name')->toArray();
        $revenues = $data->pluck('total_revenue')
            ->map(fn($v) => (float) $v) 
            ->toArray();

        return $this->chart->pieChart()
            ->setTitle('Tỉ lệ doanh thu theo danh mục')
            ->setSubtitle('Phân bổ tổng doanh thu theo danh mục')
            ->addData($revenues)
            ->setLabels($labels)
            ->setColors(['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#6366f1', '#06b6d4', '#f97316'])
            ->setHeight(300);
    }
}