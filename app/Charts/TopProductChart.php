<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class TopProductChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        return $this->chart->barChart()
            ->setTitle('Top 5 sản phẩm bán chạy')
            ->addData('Số lượng bán', [120, 90, 75, 60, 45])
            ->setLabels(['Tai nghe', 'Bàn phím', 'Chuột', 'Màn hình', 'Laptop'])
            ->setColors(['#10b981'])
            ->setHeight(300);
    }
}
