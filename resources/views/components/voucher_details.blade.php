<h3 class="text-xl font-bold mb-4">Voucher Details</h3>
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-800 text-left">
    <div class="grid grid-cols-2 gap-6">
        <div class="space-y-3">
            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">Code:</span>
                <span class="text-gray-900 flex-grow">{{ $row->code ?? 'N/A' }}</span>
            </div>

            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">Type:</span>
                <span class="text-gray-900 flex-grow">{{ $row->discount_type ?? 'N/A' }}</span>
            </div>

            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">Value:</span>
                <span class="text-gray-900 flex-grow">{{ $row->discount_value ?? 'N/A' }}</span>
            </div>  
        </div>

        <div class="space-y-3">
            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">Status:</span>
                <span class="text-gray-900 flex-grow">{{ $row->status ?? 'N/A' }}</span>
            </div>
            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">Start:</span>
                <span class="text-gray-900 flex-grow">{{ $row->start_date ?? 'N/A' }}</span>
            </div>
            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">End:</span>
                <span class="text-gray-900 flex-grow">{{ $row->end_date ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>