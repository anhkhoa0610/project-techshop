<?php

namespace App\Charts;

use App\Models\User; // Phải import Model User của bạn
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon; // Thư viện giúp xử lý ngày tháng

class UserGrowthChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LarapexChart
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subMonths(11)->startOfMonth(); 

        $monthsData = [];
        $monthsLabels = [];
        $currentMonth = clone $startDate;

        while ($currentMonth <= $endDate) {
            $monthKey = $currentMonth->format('Y-m'); // Key: 2024-10
            $monthsLabels[] = $currentMonth->format('m/Y'); // Label: 10/2024
            $monthsData[$monthKey] = 0;
            $currentMonth->addMonth();
        }

        $userGrowthData = User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_key'),
                DB::raw('COUNT(user_id) as total_new_users') 
            )
            ->whereBetween('created_at', [$startDate, $endDate->endOfMonth()])
            ->groupBy('month_key')
            ->orderBy('month_key', 'asc')
            ->get();

        foreach ($userGrowthData as $data) {
            $monthsData[$data->month_key] = (int) $data->total_new_users;
        }

        return $this->chart->areaChart()
            ->setTitle('Tăng trưởng người dùng')
            ->setSubtitle('Người dùng mới trong 12 tháng gần nhất')
            ->addData('Người dùng mới', array_values($monthsData)) 
            ->setLabels($monthsLabels)
            ->setColors(['#6366f1'])
            ->setHeight(280);
    }
}