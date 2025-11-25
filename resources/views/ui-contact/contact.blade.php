@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-filter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-chatbot.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">

    <div class=" background-layout container-layout">
        <div class="right glass3d">
            <h2>Liên hệ với chúng tôi</h2>
            <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                @csrf

                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input type="text" id="name" name="name" placeholder="Nhập họ và tên" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email" required>
                </div>

                <div class="form-group">
                    <label for="message">Nội dung</label>
                    <textarea id="message" name="message" placeholder="Nhập nội dung tin nhắn" rows="6" required></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-submit">Gửi liên hệ</button>
                </div>
            </form>

        </div>
        <div class="left">
            <div class="map">
                <iframe width="100%" height="100%" frameborder="0" style="border:0;"
                    src="https://maps.google.com/maps?q=53%20Đ.%20Võ%20Văn%20Ngân,%20Thủ%20Đức,%20TP.HCM&output=embed"
                    allowfullscreen>
                </iframe>
            </div>

            <div class="info-grid">
                <div class="info-box">
                    <h4>Email Shop</h4>
                    <p>shop@example.com</p>
                </div>
                <div class="info-box">
                    <h4>Địa chỉ Shop</h4>
                    <p>53 Võ Văn Ngân, Thủ Đức, TP.HCM</p>
                </div>
                <div class="info-box">
                    <h4>Số điện thoại</h4>
                    <p>+84 123 456 789</p>
                </div>
                <div class="info-box">
                    <h4>Website</h4>
                    <p>techshop.com</p>
                </div>
            </div>
        </div>
    </div>
@endsection