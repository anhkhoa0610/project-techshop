<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $post->title }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: auto; }
        .content { margin-top: 30px; }
        .content img { max-width: 100%; height: auto; } /* Responsive ảnh */
        .source { margin-top: 20px; font-style: italic; }
    </style>
</head>
<body>
    <h1>{{ $post->title }}</h1>
    <p><strong>{{ $post->description }}</strong></p>

    <div class="content">
        {!! $post->content !!}
    </div>

    <p class="source">
        Nguồn: <a href="{{ $post->source_url }}" target="_blank">{{ $post->source_url }}</a>
    </p>

    <a href="{{ route('posts.index') }}">&laquo; Quay lại danh sách</a>
</body>
</html>