<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TechStore - Cửa hàng công nghệ hàng đầu Việt Nam')</title>
    <meta name="description" content="@yield('description', 'TechStore - Chuyên bán điện thoại, laptop, tai nghe chính hãng với giá tốt nhất. Bảo hành uy tín, giao hàng nhanh toàn quốc.')">
    
    <link rel="stylesheet" href="{{ asset('css/index-style.css') }}">
   
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>

    {{-- Header --}}
    @include('partials.header')

    {{-- Nội dung từng trang --}}
    @yield('content')

    {{-- Footer --}}
    @include('partials.footer')

    <script src="{{ asset('js/index-script.js') }}"></script>
</body>
</html>
