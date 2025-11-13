<?php

namespace App\Charts;

use App\Models\Order; // Phải import Model Order của bạn
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon; // Thư viện giúp xử lý ngày tháng

class RevenueChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LarapexChart
    {
        // 1. Thiết lập phạm vi thời gian (12 tháng gần nhất)
        $endDate = Carbon::now();
        // Lấy 11 tháng trước, sau đó startOfMonth để bao gồm tháng hiện tại (tổng cộng 12 tháng)
        $startDate = Carbon::now()->subMonths(11)->startOfMonth(); 

        // 2. Chuẩn bị mảng 12 tháng đầy đủ cho nhãn (labels) và dữ liệu (data)
        $monthsData = [];
        $monthsLabels = [];
        $currentMonth = clone $startDate;

        // Điền vào mảng với 12 tháng, khởi tạo doanh thu là 0
        while ($currentMonth <= $endDate) {
            $monthKey = $currentMonth->format('Y-m'); // Key: 2024-10
            $monthsLabels[] = $currentMonth->format('m/Y'); // Label: 10/2024
            $monthsData[$monthKey] = 0;
            $currentMonth->addMonth();
        }

        // 3. Truy vấn Database để lấy doanh thu
        // Lọc theo status bạn muốn tính là Doanh Thu (ví dụ: 'completed' hoặc 'delivered')
        $revenueData = Order::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_key'),
                DB::raw('SUM(total_price) as total_revenue') // Giả sử cột tổng tiền là total_amount
            )
            ->where('status', 'completed') // !!! THAY 'completed' bằng status chính xác của bạn
            ->whereBetween('created_at', [$startDate, $endDate->endOfMonth()])
            ->groupBy('month_key')
            ->orderBy('month_key', 'asc')
            ->get();

        // 4. Gộp dữ liệu truy vấn vào mảng 12 tháng
        foreach ($revenueData as $data) {
            // Cập nhật giá trị doanh thu thực tế vào tháng tương ứng
            $monthsData[$data->month_key] = (int) $data->total_revenue;
        }

        // 5. Xây dựng biểu đồ
        return $this->chart->lineChart()
            ->setTitle('Doanh thu theo tháng')
            ->setSubtitle('Tổng doanh thu 12 tháng gần nhất')
            // Lấy các giá trị doanh thu từ mảng đã xử lý
            ->addData('Doanh thu (VND)', array_values($monthsData)) 
            // Lấy các nhãn tháng đã tạo sẵn
            ->setLabels($monthsLabels)
            ->setColors(['#3b82f6'])
            ->setStroke(2, ['#3b82f6'], true)
            ->setMarkers(['#3b82f6'], 5, 10)
            ->setGrid(true)
            ->setHeight(320);
    }
}