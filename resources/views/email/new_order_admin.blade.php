@component('mail::message')
# Đơn hàng mới vừa được tạo

Xin chào Admin,  

Một đơn hàng mới đã được đặt:

- **Mã đơn hàng:** {{ $order->order_id }}
- **Tên khách hàng:** {{ $order->user->full_name ?? 'Khách vãng lai' }}
- **Email khách hàng:** {{ $order->user->email ?? 'Chưa có email' }}
- **Địa chỉ giao hàng:** {{ $order->shipping_address }}
- **Tổng tiền:** {{ number_format($order->total_price, 0, ',', '.') }} VND
- **Phương thức thanh toán:** {{ ucfirst($order->payment_method) }}
- **Trạng thái:** {{ ucfirst($order->status) }}

**Chi tiết sản phẩm:**
@component('mail::table')
| Sản phẩm | Số lượng | Giá đơn vị | Thành tiền |
| -------- | :------: | ---------: | ---------: |
@foreach ($order->orderDetails as $item)
| {{ $item->product->product_name }} | {{ $item->quantity }} | {{ number_format($item->unit_price,0,',','.') }} | {{ number_format($item->unit_price,0,',','.') }} |
@endforeach
@endcomponent

Bạn có thể truy cập hệ thống để quản lý đơn hàng.  

Cảm ơn,<br>
**TechStore**
@endcomponent
