<?php

namespace App\Charts;

use App\Models\Order; // Phải import Model Order của bạn
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class OrderStatusChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LarapexChart
    {
        $statusKeys = ['completed', 'processing', 'pending', 'cancelled']; 
        
        $statusCounts = Order::select('status', DB::raw('COUNT(order_id) as count'))
            ->whereIn('status', $statusKeys) 
            ->groupBy('status')
            ->get()
            ->keyBy('status')
            ->map(fn($item) => (int) $item->count);

        $data = [];
        $labels = [
            'completed' => 'Hoàn thành', 
            'processing' => 'Đang xử lý', 
            'pending' => 'Đang chờ', 
            'cancelled' => 'Đã huỷ'
        ];

        foreach ($statusKeys as $key) {
            $data[] = $statusCounts->get($key, 0); 
        }
        
        $chartLabels = array_values(array_intersect_key($labels, array_flip($statusKeys)));

        return $this->chart->donutChart()
            ->setTitle('Tình trạng đơn hàng')
            ->setSubtitle('Số lượng đơn hàng theo status')
            ->addData($data)
            ->setLabels($chartLabels)
            ->setColors(['#10b981', '#f59e0b', '#f97316', '#ef4444']) 
            ->setHeight(280);
    }
}