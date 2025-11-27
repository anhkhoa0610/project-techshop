 @extends('layouts.app')

@section('content')
{{-- 1. Thêm Tailwind CDN (Nếu file layout chưa có) --}}
<script src="https://cdn.tailwindcss.com"></script>

{{-- 2. Thêm CSS tùy chỉnh cho hiệu ứng --}}
<style>
    /* Hiệu ứng trôi nhẹ cho icon cảnh báo */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    /* Hiệu ứng nền background pattern nhẹ */
    .bg-pattern {
        background-color: #f9fafb;
        background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
        background-size: 20px 20px;
    }
</style>

<div class="min-h-[80vh] flex items-center justify-center bg-pattern px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-md w-full text-center space-y-8 bg-white p-10 rounded-2xl shadow-2xl border border-gray-100 transform transition-all hover:scale-[1.01] duration-300">
        
        <div class="mx-auto h-24 w-24 bg-red-50 rounded-full flex items-center justify-center mb-6 animate-float">
            <svg class="h-12 w-12 text-red-500 drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <div>
            <h1 class="text-6xl font-extrabold text-gray-900 tracking-tight drop-shadow-md">404</h1>
            <p class="mt-2 text-lg font-medium text-gray-600">Úi! Trang này không tồn tại.</p>
            <p class="mt-1 text-sm text-gray-500">{{ $message ?? 'Có vẻ như đường dẫn bạn truy cập bị hỏng hoặc đã bị xóa.' }}</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center mt-8">
            {{-- Nút quay lại --}}
            <button onclick="history.back()" class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-red-500 hover:border-red-200 transition-all duration-200 ease-in-out cursor-pointer shadow-sm active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại
            </button>
            
            {{-- Nút về danh sách --}}
            <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 hover:shadow-lg transition-all duration-200 ease-in-out shadow-md active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection