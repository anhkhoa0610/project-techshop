@props(['items' => []])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb-list">
        <li class="breadcrumb-item"><a href="/index">🏠 Trang chủ</a></li>

        @foreach ($items as $item)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $item['title'] }}
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>