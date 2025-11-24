<h3 class="text-xl font-bold mb-4">User Details</h3>
@php
    $user = App\Models\User::with('profile')->find($row->user_id);
    $profile = $user ? $user->profile : collect([]);
@endphp

<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-800 text-left">
    <h3 class="text-xl font-bold mb-4">User Details</h3>

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg">

            <div class="space-y-3">
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-2/5 shrink-0">Fullname:</span>
                    <span class="text-gray-900 flex-grow">{{ $user->full_name }}</span>
                </div>

                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-2/5 shrink-0">Address:</span>
                    <span class="text-gray-900 flex-grow">{{ $user->address }}</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-2/5 shrink-0">Email:</span>
                    <span class="text-gray-900 flex-grow">{{ $user->email }}</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-2/5 shrink-0">Phone:</span>
                    <span class="text-gray-900 flex-grow">{{ $user->phone }}</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-2/5 shrink-0">Role:</span>
                    <span class="text-gray-900 flex-grow">{{ $user->role }}</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-2/5 shrink-0">Birth:</span>
                    <span class="text-gray-900 flex-grow">{{ $user->birth }}</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-2/5 shrink-0">Student:</span>
                    <span class="text-gray-900 flex-grow">{{ $user->is_tdc_student }}</span>
                </div>

                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-2/5 shrink-0">Avatar:</span>
                    <img src="{{ asset('images/' . $profile->avatar)}}" alt="">
                </div>

            </div>
        </div>
    </div>
</div>