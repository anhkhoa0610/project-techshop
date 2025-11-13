<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class CategoryChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        return $this->chart->pieChart()
            ->setTitle('Tỉ lệ doanh thu theo danh mục')
            ->addData([40, 25, 20, 10, 5])
            ->setLabels(['Laptop', 'PC', 'Phụ kiện', 'Âm thanh', 'Khác'])
            ->setColors(['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#6366f1'])
            ->setHeight(300);
    }
}
