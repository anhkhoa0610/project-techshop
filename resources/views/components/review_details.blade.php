<h3 class="text-xl font-bold mb-4">Review Details</h3>
<div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl shadow-lg border border-blue-200">
    <!-- Header với tên người review + sản phẩm -->
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr($row->full_name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <h4 class="text-lg font-bold text-gray-800">
                    {{ $row->full_name ?? 'Khách vãng lai' }}
                </h4>
                <p class="text-sm text-gray-600">
                    Đánh giá cho: <span class="font-medium text-indigo-700">{{ $row->product_name ?? 'Sản phẩm bí ẩn' }}</span>
                </p>
            </div>
        </div>

        <!-- Rating ngôi sao to đùng -->
        <div class="text-right">
            <div class="flex items-center justify-end space-x-1">
                @for($i = 1; $i <= 5; $i++)
                    <span class="text-3xl {{ $i <= ($row->rating ?? 0) ? 'text-yellow-500' : 'text-gray-300' }}">
                        {{ $i <= ($row->rating ?? 0) ? '★' : '☆' }}
                    </span>
                @endfor
            </div>
            <p class="text-2xl font-bold text-indigo-700 mt-1">{{ $row->rating ?? 0 }}/5</p>
        </div>
    </div>

    <!-- Bình luận -->
    <div class="bg-white rounded-lg p-5 mb-4 shadow-sm border-l-4 border-indigo-600">
        <p class="text-gray-800 leading-relaxed whitespace-pre-wrap text-base">
            {!! nl2br(e($row->comment ?? '<em class="text-gray-500">Chưa có bình luận (lười viết hay ngại viết? 🤔)</em>')) !!}
        </p>
    </div>

    <!-- Ngày đánh giá -->
    <div class="flex justify-between items-center text-sm text-gray-600">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>
                Ngày đánh giá: 
                <strong class="text-indigo-700">
                    {{ $row->review_date ? \Carbon\Carbon::parse($row->review_date)->format('d/m/Y') : 'Chưa rõ (ẩn danh thời gian)' }}
                </strong>
            </span>
        </div>

        @if($row->rating && $row->rating >= 5)
            <span class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-bold animate-pulse">
                VIP REVIEWER ⭐
            </span>
        @elseif($row->rating && $row->rating <= 1)
            <span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-bold">
                Hater chính hiệu
            </span>
        @endif
    </div>
</div>