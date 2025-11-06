<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Danh sách tin tức</title>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            padding: 20px;
        }

        .post-item {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .post-item h2 {
            margin: 0;
        }

        .pagination {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <h1>Tin tức Công nghệ</h1>

    @foreach ($posts as $post)
        <article class="post-item">

            @if ($post->cover_image)
                <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" style="max-width: 300px; height: auto;">
            @endif

            <h2>
                <a href="{{ route('posts.show', $post->id) }}">
                    {{ $post->title }}
                </a>
            </h2>
            <p>{{ $post->description }}</p>
            <small>Cập nhật lúc: {{ $post->updated_at->format('d/m/Y H:i') }}</small>
        </article>
    @endforeach

    <div class="pagination">
        {{ $posts->links() }}
    </div>

</body>

</html>