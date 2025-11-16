<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response; // Import Facade Response

class ExportController extends Controller
{
    public function exportInvoice($orderId)
    {
        $order = Order::with('items.product')->find($orderId); // Đảm bảo bạn load cả product

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        // 1. Định dạng nội dung hóa đơn (Tạo bố cục đẹp cho file TXT/CSV)
        $content = $this->formatInvoiceContent($order);

        // 2. Thiết lập Header để trình duyệt tải file
        $fileName = 'Hoa_Don_DH_' . $orderId . '.txt';
        $headers = [
            'Content-Type'        => 'text/plain', // Loại nội dung là Plain Text
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        // 3. Trả về Response
        return Response::make($content, 200, $headers);
    }
    
    // Hàm phụ trợ để định dạng nội dung
    private function formatInvoiceContent($order)
    {
        // ----------------------------------------------------
        // I. HEADER
        // ----------------------------------------------------
        $content = "====== HOA DON MUA HANG - TECHSTORE ======\n";
        $content .= "Ma don hang: #" . $order->order_id . "\n";
        $content .= "Ngay dat:    " . $order->order_date . "\n";
        $content .= "Trang thai:  " . ucfirst($order->status) . "\n";
        $content .= str_repeat('-', 40) . "\n";
        
        // ----------------------------------------------------
        // II. THONG TIN KHÁCH HÀNG (Giả định lấy được từ auth() hoặc order)
        // ----------------------------------------------------
        $content .= "Khach hang: " . ($order->user->name ?? 'Nguoi dung') . "\n";
        $content .= "Dia chi:    " . ($order->shipping_address ?? 'Chua co dia chi') . "\n";
        $content .= str_repeat('-', 40) . "\n";

        // ----------------------------------------------------
        // III. CHI TIẾT SẢN PHẨM (Dạng bảng ASCII)
        // ----------------------------------------------------
        $content .= "CHI TIET SAN PHAM:\n";
        
        // Header Bảng
        $content .= str_pad("Ten San Pham", 25) . str_pad("SL", 5) . str_pad("Thanh Tien (d)", 10, " ", STR_PAD_LEFT) . "\n";
        $content .= str_repeat('-', 40) . "\n";
        
        // Chi tiết từng sản phẩm
        foreach ($order->items as $item) {
            $productName = substr($item->product->name, 0, 24); // Cắt bớt tên sản phẩm
            $quantity = $item->quantity;
            $subtotal = number_format($item->unit_price, 0, '', '.');

            $content .= str_pad($productName, 25) 
                     . str_pad($quantity, 5) 
                     . str_pad($subtotal, 10, " ", STR_PAD_LEFT) 
                     . "\n";
        }
        $content .= str_repeat('-', 40) . "\n";

        // ----------------------------------------------------
        // IV. TỔNG KẾT
        // ----------------------------------------------------
        $totalFormatted = number_format($order->total_price, 0, '', '.');
        $content .= str_pad("TONG CONG:", 30) . str_pad($totalFormatted . " d", 10, " ", STR_PAD_LEFT) . "\n";
        $content .= str_repeat('=', 40) . "\n";

        return $content;
    }
}