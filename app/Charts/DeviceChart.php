<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class DeviceChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        return $this->chart->barChart()
            ->setTitle('Thiết bị truy cập website')
            ->addData('Lượt truy cập', [65, 25, 10])
            ->setLabels(['Mobile', 'Desktop', 'Tablet'])
            ->setColors(['#3b82f6'])
            ->setHeight(280);
    }
}
