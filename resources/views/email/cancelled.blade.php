<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p># Đơn hàng #{{ $order->order_id }} đã bị hủy</p>

    <h2>Xin chào {{ $order->user->full_name }},</h2>

    <p>Đơn hàng của bạn đã bị hủy. Nếu có thắc mắc vui lòng liên hệ với chúng tôi.</p>

    <p>Cảm ơn, </p>
    <p>TechStore</p>

</body>

</html>