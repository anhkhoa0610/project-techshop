<?php

namespace App\Charts;

use App\Models\Product; // Import Model Product
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Str; // Import Str facade để rút gọn chuỗi

class TopProductChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LarapexChart
    {
        $topProducts = Product::select('product_name', 'volume_sold')
            ->orderByDesc('volume_sold')
            ->limit(5)                
            ->get();
            
        
        $productLabels = $topProducts->pluck('product_name')
            ->map(fn($name) => (string) Str::limit($name, 30, '...')) // Rút gọn
            ->toArray();
            
        $salesData = $topProducts->pluck('volume_sold')
            ->map(fn($v) => (int) $v)
            ->toArray();

        // 2. Xây dựng biểu đồ
        return $this->chart->horizontalBarChart()
            ->setTitle('Top 5 sản phẩm bán chạy')
            ->setSubtitle('Dựa trên cột volume_sold đã được tổng hợp trong bảng Sản phẩm')
            ->addData('Số lượng bán', $salesData)
            ->setLabels($productLabels)
            ->setColors(['#10b981'])
            ->setHeight(300);
    }
}