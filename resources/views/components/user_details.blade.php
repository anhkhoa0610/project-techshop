<h3 class="text-xl font-bold mb-4">User Details</h3>

@php
    // Lấy user + profile, nếu không có thì cho về collect() để tránh lỗi
    $user    = $row instanceof \App\Models\User ? $row : \App\Models\User::with('profile')->find($row->user_id);
    $profile = $user?->profile;
    
    // Avatar an toàn 100%
    $avatarUrl = $profile?->avatar 
        ? asset('images/' . $profile->avatar) 
        : asset('images/avata/user-icon.png');
@endphp

<div class="p-8 bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg border border-gray-100">
    <!-- Header đẹp lung linh -->
    <div class="flex items-center gap-6 mb-8 border-b border-gray-200 pb-6">
        <div class="relative">
            <img src="{{ $avatarUrl }}"
                 alt="{{ $user->full_name }}"
                 class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-xl ring-4 ring-blue-100">
            @if($user->role === 'Admin')
                <span class="absolute -top-1 -right-1 bg-purple-600 text-white text-xs px-2 py-1 rounded-full font-bold shadow">
                    ADMIN
                </span>
            @elseif($user->is_tdc_student === 'true')
                <span class="absolute -top-1 -right-1 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-bold shadow">
                    SV TDC
                </span>
            @endif
        </div>

        <div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $user->full_name }}</h3>
            <p class="text-gray-600 flex items-center gap-2 mt-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                </svg>
                {{ $user->email }}
            </p>
            @if($profile?->bio)
                <p class="text-sm text-gray-500 italic mt-2 max-w-2xl">"{{ $profile->bio }}"</p>
            @endif
        </div>
    </div>

    <!-- Thông tin chi tiết dạng lưới 2 cột đẹp mắt -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
        <!-- Cột trái -->
        <div class="space-y-5">
            <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl">
                <div class="p-3 bg-blue-200 rounded-lg">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider">Số điện thoại</p>
                    <p class="font-semibold text-gray-800">{{ $user->phone ?: 'Chưa cập nhật' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 bg-purple-50 rounded-xl">
                <div class="p-3 bg-purple-200 rounded-lg">
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider">Địa chỉ</p>
                    <p class="font-semibold text-gray-800">{{ $user->address ?: 'Chưa cập nhật' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 bg-yellow-50 rounded-xl">
                <div class="p-3 bg-yellow-200 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider">Ngày sinh</p>
                    <p class="font-semibold text-gray-800">
                        {{ $user->birth?->isoFormat('DD/MM/YYYY') ?? 'Chưa cập nhật' }}
                        @if($user->birth)
                            <span class="block text-xs text-gray-500 mt-1">
                                ({{ $user->birth->age }} tuổi
                                @if($user->birth->isBirthday(today())) → Hôm nay sinh nhật nè! 🎉 @endif)
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Cột phải -->
        <div class="space-y-5">
            <div class="flex items-center gap-4 p-4 bg-indigo-50 rounded-xl">
                <div class="p-3 bg-indigo-200 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider">Vai trò</p>
                    <p class="font-bold text-indigo-700">
                        {{ $user->role === 'Admin' ? 'Quản trị viên' : 'Người dùng thường' }}
                    </p>
                </div>
            </div>

            @if($profile?->website)
            <div class="flex items-center gap-4 p-4 bg-pink-50 rounded-xl">
                <div class="p-3 bg-pink-200 rounded-lg">
                    <svg class="w-6 h-6 text-pink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03- ocup-9s1.343-9 3-9"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider">Website</p>
                    <a href="{{ $profile->website }}" target="_blank" class="font-semibold text-pink-600 hover:underline">
                        {{ Str::limit($profile->website, 40) }}
                    </a>
                </div>
            </div>
            @endif

            <div class="p-4 bg-gray-100 rounded-xl text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Tham gia lúc</p>
                <p class="font-bold text-gray-700">
                    {{ $user->created_at->isoFormat('HH:mm - DD/MM/YYYY') }}
                </p>
            </div>
        </div>
    </div>
</div>