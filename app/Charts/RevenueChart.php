<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class RevenueChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LarapexChart
    {
        return $this->chart->lineChart()
            ->setTitle('Doanh thu theo tháng')
            ->setSubtitle('Tổng doanh thu 12 tháng gần nhất')
            ->addData('Doanh thu', [120, 150, 180, 220, 260, 300, 320, 310, 290, 340, 360, 400])
            ->setLabels(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'])
            ->setColors(['#3b82f6'])
            ->setStroke(2, ['#3b82f6'], true) // 🌊 đường cong mềm mượt
            ->setMarkers(['#3b82f6'], 5, 10)   // ⚪ chấm nhỏ trên điểm dữ liệu
            ->setGrid(true)
            ->setHeight(320);
    }
}
