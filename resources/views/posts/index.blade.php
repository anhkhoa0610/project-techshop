@extends('layouts.layouts')

@section('title', 'Tin tức')

@section('content')
    {{-- 1. CSS ĐỂ TRANG TRÍ --}}
    <style>
        .background-overlay {
            position: relative !important;
            overflow: hidden !important;
            background-image: none !important;
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


        .post-list-container {
            max-width: 1200px;
            margin: 0px auto;
            padding: 20px;
            padding-top: 100px;
        }

        h1.page-title {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
        }

        /* Vùng chứa danh sách post */
        .post-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        /* Thiết kế thẻ Card cho mỗi post */
        .post-item {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .post-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        /* Phần ảnh */
        .post-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        /* Phần nội dung */
        .post-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .post-content h2 {
            font-size: 1.25rem;
            margin: 0 0 10px 0;
        }

        .post-content h2 a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }

        .post-content h2 a:hover {
            color: #007bff;
        }

        /* Mô tả */
        .post-description {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.5;
            flex-grow: 1;
            margin-bottom: 15px;
        }

        .post-meta {
            font-size: 0.85rem;
            color: #999;
        }

        .load-more-container {
            text-align: center;
            margin-top: 40px;
        }

        .load-more-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .load-more-btn:hover {
            background-color: #0056b3;
        }

        .load-more-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
            display: none;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>


    <div class="container-fluid background-overlay">
        <div class="post-list-container">
            <div class="breadcrumb-container">
                <x-breadcrumb :items="[
            ['title' => 'Tin tức']]" />                      
            </div>

            <h1 class="page-title text-white">Tin tức Công nghệ</h1>

            <div class="post-list" id="post-list">

                @foreach ($posts as $post)
                    <article class="post-item">
                        @php
                            $imageUrl = $post->cover_image ?
                                $post->cover_image :
                                'https://via.placeholder.com/300x200.png?text=No+Image';
                        @endphp

                        <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="post-image">

                        <div class="post-content">
                            <h2>
                                <a href="{{ route('posts.show', $post->id) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            <p class="post-description">{{ $post->description }}</p>
                            <small class="post-meta">Cập nhật lúc: {{ $post->updated_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="load-more-container">
                <div class="loader" id="loader"></div>

                @if ($posts->hasMorePages())
                    <button class="load-more-btn" id="load-more-btn">
                        Xem thêm tin tức
                    </button>
                @endif
            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const loadMoreBtn = document.getElementById('load-more-btn');
            const postList = document.getElementById('post-list');
            const loader = document.getElementById('loader');

            let currentPage = 1;

            if (!loadMoreBtn) {
                return;
            }

            loadMoreBtn.addEventListener('click', function () {
                currentPage++;

                loader.style.display = 'block';
                loadMoreBtn.disabled = true;

                fetch(`/api/posts?page=${currentPage}`)
                    .then(response => response.json())
                    .then(data => {
                        loader.style.display = 'none';
                        loadMoreBtn.disabled = false;


                        if (data.success && data.data) {

                            data.data.forEach(post => {
                                const postHtml = createPostHtml(post);
                                postList.insertAdjacentHTML('beforeend', postHtml);
                            });

                            if (data.current_page >= data.last_page) {
                                loadMoreBtn.style.display = 'none';
                            }

                        } else {
                            console.error('API không trả về dữ liệu thành công.');
                            loadMoreBtn.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi tải thêm bài viết:', error);
                        loader.style.display = 'none';
                        loadMoreBtn.disabled = false;
                    });
            });

            function createPostHtml(post) {
                const imageUrl = post.cover_image ?
                    post.cover_image :
                    'https://via.placeholder.com/300x200.png?text=No+Image';

                const postDate = new Date(post.updated_at)
                    .toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                const postUrl = `/tin-tuc/${post.id}`;

                return `
                                            <article class="post-item">
                                                <img src="${imageUrl}" alt="${post.title}" class="post-image">

                                                <div class="post-content">
                                                    <h2>
                                                        <a href="${postUrl}">${post.title}</a>
                                                    </h2>
                                                    <p class="post-description">${post.description || ''}</p>
                                                    <small class="post-meta">Cập nhật lúc: ${postDate}</small>
                                                </div>
                                            </article>
                                        `;
            }
        });
    </script>
@endsection