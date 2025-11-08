@extends('layouts.layouts')
@section('title', $post->title)

@section('content')

    <style>
        .breadcrumb-container {
            margin-left: 15vw;
        }

        .background-overlay {
            position: relative !important;
            overflow: hidden !important;
            background-image: none !important;
            padding-top: 100px;
        }

        .background-overlay::before {
            content: "" !important;
            position: absolute !important;
            inset: 0 !important;
            background-image: url('/images/background.jpg') !important;
            background-size: cover !important;
            background-repeat: no-repeat !important;
            background-position: center center !important;
            z-index: 0 !important;
            transform-origin: center center !important;
            will-change: transform !important;
            animation: kenburns 18s ease-in-out infinite alternate !important;
        }

        .background-overlay::after {
            content: "" !important;
            position: absolute !important;
            inset: 0 !important;
            background: rgba(0, 0, 0, 0.4) !important;
            z-index: 1 !important;
        }

        .background-overlay>* {
            position: relative !important;
            z-index: 2 !important;
        }

        @keyframes kenburns {
            0% {
                transform: scale(1) translate(0, 0);
            }

            50% {
                transform: scale(1.12) translate(-2%, -2%);
            }

            100% {
                transform: scale(1) translate(0, 0);
            }
        }


        .post-detail-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 25px;
            border-radius: 8px;
            line-height: 1.7;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        /* --- TIÊU ĐỀ BÀI VIẾT --- */
        .post-detail-container h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
            color: #ffffffff;
        }

        .post-detail-container p strong {
            font-size: 1.15rem;
            /* 18px */
            color: #cececeff;
            font-weight: 600;
            /* In đậm vừa phải */
            display: block;
            margin-bottom: 25px;
            border-left: 4px solid #007bff;
            /* Tạo điểm nhấn */
            padding-left: 15px;
        }

        /* --- NỘI DUNG CHÍNH (QUAN TRỌNG NHẤT) --- */
        .content {
            font-size: 1.05rem;
            /* 17px */
            color: #e0e0e0ff;
        }

        /* Định dạng các thẻ p bên trong nội dung */
        .content p {
            margin-bottom: 1.5em;
            /* 1.5 lần kích cỡ font */
        }

        /* Định dạng ảnh bên trong nội dung */
        .content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }

        .alignCenter {
            background-color: transparent !important;
            color: #e0e0e0ff !important;
        }

        .content ul,
        .content ol {
            margin-bottom: 1.5em;
            padding-left: 2em;
        }

        /* Định dạng trích dẫn (nếu có) */
        .content blockquote {
            font-style: italic;
            color: #a3a3a3ff;
            margin: 20px 0;
            padding: 15px 20px;
        }

        /* --- THÔNG TIN NGUỒN VÀ LINK QUAY LẠI --- */
        .source {
            font-size: 0.9rem;
            color: #888;
            font-style: italic;
            margin-top: 30px;
            border-top: 1px solid #eee;
            /* Phân cách khỏi nội dung */
            padding-top: 15px;
        }

        .source a {
            color: #555;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 0.95rem;
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            transition: transform 0.2s ease;
        }

        .back-link:hover {
            text-decoration: underline;
            transform: translateX(-5px);
        }
    </style>

    <div class="container-fluid background-overlay">
        <div class="breadcrumb-container">
            <x-breadcrumb :items="[
            ['title' => 'Tin tức', 'url' => route('posts.index')],
            ['title' => $post->title]
        ]" />
        </div>
        <div class="post-detail-container glass3d">

            <h1>{{ $post->title }}</h1>
            <p><strong>{{ $post->description }}</strong></p>

            <div class="content">
                {!! $post->content !!}
            </div>

            <p class="source">
                Nguồn: <a href="{{ $post->source_url }}" target="_blank">{{ $post->source_url }}</a>
            </p>

            {{-- Thêm class "back-link" để dễ dàng trang trí --}}
            <a href="{{ route('posts.index') }}" class="back-link">&laquo; Quay lại danh sách</a>

        </div>
    </div>
@endsection