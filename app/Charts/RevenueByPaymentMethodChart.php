<?php

namespace App\Charts;

use App\Models\Order; // Phải import Model Order của bạn
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class RevenueByPaymentMethodChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LarapexChart
    {
        // 1. Định nghĩa các phương thức thanh toán chính xác của bạn
        $paymentMethods = [
            'cash'      => 'Tiền mặt (COD)', 
            'momo'      => 'Thanh toán Momo', 
            'vnpay'     => 'Thanh toán VNPay',
            // Loại bỏ 'card' và 'transfer' vì bạn không sử dụng
        ];

        $methodKeys = array_keys($paymentMethods);
        
        // 2. Truy vấn Database: Tính tổng doanh thu theo Phương thức Thanh toán
        $revenueData = Order::select(
                'payment_method', 
                DB::raw('SUM(total_price) as total_revenue') 
            )
            ->where('status', 'completed') // Chỉ tính các đơn hàng đã hoàn thành (có doanh thu)
            ->whereIn('payment_method', $methodKeys) // Chỉ lấy các phương thức đã định nghĩa
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method')
            ->map(fn($item) => (float) $item->total_revenue);

        // 3. Chuẩn bị mảng Data (doanh thu) và Labels (tên hiển thị)
        $data = [];
        $labels = [];

        // Lấp đầy mảng data và labels theo đúng thứ tự
        foreach ($methodKeys as $key) {
            // Lấy doanh thu, nếu không có thì là 0
            $data[] = $revenueData->get($key, 0); 
            $labels[] = $paymentMethods[$key];
        }

        // 4. Xây dựng biểu đồ
        return $this->chart->donutChart()
            ->setTitle('Phương thức Thanh toán')
            ->setSubtitle('Đóng góp vào doanh thu')
            ->addData($data)
            ->setLabels($labels)
            // Thiết lập màu sắc phù hợp cho 3 phương thức: Cash, Momo, VNPay
            ->setColors(['#f59e0b', '#ef4444', '#3b82f6']) 
            ->setHeight(300);
    }
}