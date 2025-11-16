<h3 class="text-xl font-bold mb-4">Voucher Details</h3>
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-800 text-left">
    <div class="grid grid-cols-2 gap-6">
        <div class="space-y-3">
            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">Product count:</span>
                <span class="text-gray-900 flex-grow">{{ $row->products_count ?? 0 }}</span>
            </div>

            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">Quantity sold:</span>
                <span class="text-gray-900 flex-grow">{{ $row->order_details_sum_quantity ?? 0 }}</span>
            </div>
        </div>

        <div class="border-l pl-4">
            <span class="font-semibold text-gray-700 block mb-1">Description:</span>
            <p class="text-gray-900 text-sm italic whitespace-normal">
                {{ html_entity_decode($row->description ?? 'N/A') }}
            </p>
        </div>
    </div>
</div>