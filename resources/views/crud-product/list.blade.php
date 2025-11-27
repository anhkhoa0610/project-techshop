@extends('layouts.dashboard')
@section('content')
    <!-- Main Content -->
    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @livewire('product-table')
        </div>
    </main>

    <!-- Modal Thêm Mới Sản Phẩm -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form id="addProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content rounded-xl shadow-2xl border-0">
                    <div
                        class="modal-header bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-t-xl border-0 py-4">
                        <h5 class="modal-title text-2xl font-bold flex items-center gap-2" id="addProductModalLabel">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Thêm mới sản phẩm
                        </h5>
                        <button type="button" class="close text-white opacity-80 hover:opacity-100 transition-opacity"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-3xl">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body bg-gray-50 p-6">
                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="add_product_name" class="block text-sm font-semibold text-gray-700 mb-2">Tên
                                        sản phẩm</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_product_name" name="product_name">
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_product_name"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="add_description" class="block text-sm font-semibold text-gray-700 mb-2">Mô
                                        tả</label>
                                    <textarea
                                        class="form-control rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_description" name="description" rows="3"></textarea>
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_description"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="add_stock_quantity"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Số lượng tồn</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_stock_quantity" name="stock_quantity" inputmode="numeric">
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_stock_quantity"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="add_release_date"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Ngày phát hành</label>
                                    <input type="date"
                                        class="form-control rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_release_date" name="release_date">
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_release_date"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="add_supplier_id" class="block text-sm font-semibold text-gray-700 mb-2">Nhà
                                        cung cấp</label>
                                    <select
                                        class="form-control rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_supplier_id" name="supplier_id">
                                        <option value="">-- Chọn nhà cung cấp --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_supplier_id"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="add_category_id" class="block text-sm font-semibold text-gray-700 mb-2">Danh
                                        mục</label>
                                    <select
                                        class="form-control rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_category_id" name="category_id">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_category_id"></div>
                                </div>

                            </div>
                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="add_cover_image" class="block text-sm font-semibold text-gray-700 mb-2">Hình
                                        ảnh</label>
                                    <div
                                        class="mt-2 text-center bg-white p-4 rounded-lg border-2 border-dashed border-gray-300">
                                        <img id="add_preview_image" src="{{ asset('images/place-holder.jpg') }}"
                                            alt="Ảnh sản phẩm" class="mx-auto rounded-lg shadow-md"
                                            style="max-width: 180px;">
                                    </div>
                                    <input type="file"
                                        class="form-control mt-3 rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_cover_image" name="cover_image" accept="image/*">
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_cover_image"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="add_price"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Giá</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_price" name="price" inputmode="decimal">
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_price"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="add_volume_sold" class="block text-sm font-semibold text-gray-700 mb-2">Đã
                                        bán</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_volume_sold" name="volume_sold" inputmode="numeric">
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_volume_sold"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="add_warranty_period"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Bảo hành</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_warranty_period" name="warranty_period" inputmode="numeric">
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_warranty_period">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="add_embed_url_review"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Link review</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition"
                                        id="add_embed_url_review" name="embed_url_review">
                                    <div class="text-danger error-message text-sm mt-1" id="error_add_embed_url_review">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-t border-gray-200 px-6 py-4 rounded-b-xl">
                        <button type="button"
                            class="btn btn-secondary px-6 py-2.5 rounded-lg hover:bg-gray-600 transition-colors"
                            data-dismiss="modal">Đóng</button>
                        <button type="submit"
                            class="btn btn-success px-6 py-2.5 rounded-lg bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold shadow-lg hover:shadow-xl transition-all">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Thêm mới
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal Edit Product -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_updated_at" name="updated_at" value="">
                <div class="modal-content rounded-xl shadow-2xl border-0">
                    <div
                        class="modal-header bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-t-xl border-0 py-4">
                        <h5 class="modal-title text-2xl font-bold flex items-center gap-2" id="editProductModalLabel">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Chỉnh sửa sản phẩm
                        </h5>
                        <button type="button" class="close text-white opacity-80 hover:opacity-100 transition-opacity"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-3xl">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body bg-gray-50 p-6">
                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="edit_product_name"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Tên sản phẩm</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_product_name" name="product_name">
                                    <div class="text-danger error-message text-sm mt-1" id="error_edit_product_name">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="edit_description"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Mô tả</label>
                                    <textarea
                                        class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_description" name="description" rows="3"></textarea>
                                    <div class="text-danger error-message text-sm mt-1"
                                        id="error_edit_product_description"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="edit_stock_quantity"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Số lượng tồn</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_stock_quantity" name="stock_quantity" inputmode="numeric">
                                    <div class="text-danger error-message text-sm mt-1" id="error_edit_stock_quantity">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="edit_release_date"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Ngày phát hành</label>
                                    <input type="date"
                                        class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_release_date" name="release_date">
                                    <div class="text-danger error-message text-sm mt-1" id="error_edit_release_date">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="edit_supplier_id"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Nhà cung cấp</label>
                                    <select
                                        class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_supplier_id" name="supplier_id">
                                        <option value="">-- Chọn nhà cung cấp --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message text-sm mt-1" id="error_edit_supplier_id"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="edit_category_id"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Danh mục</label>
                                    <select
                                        class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_category_id" name="category_id">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_category_id"></div>
                                </div>

                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="edit_cover_image"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh</label>
                                    <div
                                        class="mt-2 text-center bg-white p-4 rounded-lg border-2 border-dashed border-gray-300">
                                        <img id="preview_image" src="" alt="Ảnh sản phẩm"
                                            class="mx-auto rounded-lg shadow-md" style="max-width: 180px;">
                                    </div>
                                    <input type="file"
                                        class="form-control mt-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_cover_image" name="cover_image" accept="image/*">
                                    <div class="text-danger error-message text-sm mt-1" id="error_edit_cover_image"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="edit_price"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Giá</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_price" name="price" inputmode="decimal">
                                    <div class="text-danger error-message text-sm mt-1" id="error_edit_price"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="edit_volume_sold"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Đã bán</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_volume_sold" name="volume_sold" inputmode="numeric">
                                    <div class="text-danger error-message text-sm mt-1" id="error_edit_volume_sold"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="edit_warranty_period"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Bảo hành</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_warranty_period" name="warranty_period" inputmode="numeric">
                                    <div class="text-danger error-message text-sm mt-1" id="error_edit_warranty_period">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="edit_embed_url_review"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Link review</label>
                                    <input type="text"
                                        class="form-control rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                                        id="edit_embed_url_review" name="embed_url_review">
                                    <div class="text-danger error-message text-sm mt-1" id="error_edit_embed_url_review">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-white border-t border-gray-200 px-6 py-4 rounded-b-xl">
                        <button type="button"
                            class="btn btn-secondary px-6 py-2.5 rounded-lg hover:bg-gray-600 transition-colors"
                            data-dismiss="modal">Đóng</button>
                        <button type="submit"
                            class="btn btn-primary px-6 py-2.5 rounded-lg bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold shadow-lg hover:shadow-xl transition-all">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Lưu thay đổi
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script src="js/crud-product.js"></script>
    @endpush
@endsection
