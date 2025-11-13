<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class OrderStatusChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        return $this->chart->donutChart()
            ->setTitle('Tình trạng đơn hàng')
            ->addData([60, 25, 10, 5])
            ->setLabels(['Hoàn thành', 'Đang xử lý', 'Đã huỷ', 'Hoàn tiền'])
            ->setColors(['#10b981', '#f59e0b', '#ef4444', '#3b82f6'])
            ->setHeight(280);
    }
}
