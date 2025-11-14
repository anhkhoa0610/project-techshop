<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-800 text-left">
    <h3 class="text-xl font-bold mb-4 border-b pb-2">Chi tiết sản phẩm</h3>
    <div class="grid grid-cols-2 gap-6">
        <div class="space-y-3">
            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">Release Date:</span>
                <span class="text-gray-900 flex-grow">{{ $row->release_date ?? 'N/A' }}</span>
            </div>

            <div class="flex items-start">
                <span class="font-semibold text-gray-700 w-2/5 shrink-0">Review URL:</span>
                <span class="text-blue-600 hover:underline flex-grow">
                    <a href="{{ $row->embed_url_review ?? '#' }}" target="_blank" rel="noopener noreferrer">
                        {{ $row->embed_url_review ?? 'N/A' }}
                    </a>
                </span>
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