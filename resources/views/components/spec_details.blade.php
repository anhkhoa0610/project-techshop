<h3 class="text-xl font-bold mb-4">Spec Details</h3>
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-800 text-left">
    <div class="grid grid-cols-2 gap-6">
        <div class="space-y-3">
            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">Product name:</span>
                <span class="text-gray-900 flex-grow">{{ $row->product_name_display ?? "" }}</span>
            </div>
        </div>
        <div class="space-y-3">
            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">{{ $row->name ?? "" }}:</span>
                <span class="text-gray-900 flex-grow">{{ $row->value ?? "" }}</span>
            </div>  
        </div>
    </div>
</div>