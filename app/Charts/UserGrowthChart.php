<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class UserGrowthChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        return $this->chart->areaChart()
            ->setTitle('Tăng trưởng người dùng')
            ->setSubtitle('Số lượng người dùng đăng ký mới')
            ->addData('Người dùng mới', [30, 45, 50, 70, 85, 100, 90])
            ->setLabels(['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'])
            ->setColors(['#6366f1'])
            ->setHeight(280);
    }
}
