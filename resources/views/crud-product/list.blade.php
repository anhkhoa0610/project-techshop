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
    <div class="modal-dialog" role="document">
        <form id="addProductForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Thêm mới sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Cột trái -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_product_name">Tên sản phẩm</label>
                                <input type="text" class="form-control" id="add_product_name" name="product_name">
                                <div class="text-danger error-message" id="error_add_product_name"></div>
                            </div>

                            <div class="form-group">
                                <label for="add_description">Mô tả</label>
                                <textarea class="form-control" id="add_description" name="description"></textarea>
                                <div class="text-danger error-message" id="error_add_description"></div>
                            </div>

                            <div class="form-group">
                                <label for="add_stock_quantity">Số lượng tồn</label>
                                <input type="text" class="form-control" id="add_stock_quantity" name="stock_quantity" inputmode="numeric">
                                <div class="text-danger error-message" id="error_add_stock_quantity"></div>
                            </div>

                            <div class="form-group">
                                <label for="add_release_date">Ngày phát hành</label>
                                <input type="date" class="form-control" id="add_release_date" name="release_date">
                                <div class="text-danger error-message" id="error_add_release_date"></div>
                            </div>

                            <div class="form-group">
                                <label for="add_supplier_id">Nhà cung cấp</label>
                                <select class="form-control" id="add_supplier_id" name="supplier_id">
                                    <option value="">-- Chọn nhà cung cấp --</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger error-message" id="error_add_supplier_id"></div>
                            </div>

                            <div class="form-group">
                                <label for="add_category_id">Danh mục</label>
                                <select class="form-control" id="add_category_id" name="category_id">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger error-message" id="error_add_category_id"></div>
                            </div>

                        </div>
                        <!-- Cột phải -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_cover_image">Hình ảnh</label>
                                <div class="mt-2 text-center">
                                    <img id="add_preview_image" src="{{ asset('images/place-holder.jpg') }}"
                                        alt="Ảnh sản phẩm" style="max-width: 120px; border-radius: 6px;">
                                </div>
                                <input type="file" class="form-control" id="add_cover_image" name="cover_image"
                                    accept="image/*">
                                <div class="text-danger error-message" id="error_add_cover_image"></div>
                            </div>

                            <div class="form-group">
                                <label for="add_price">Giá</label>
                                <input type="text" class="form-control" id="add_price" name="price" inputmode="decimal">
                                <div class="text-danger error-message" id="error_add_price"></div>
                            </div>

                            <div class="form-group">
                                <label for="add_volume_sold">Đã bán</label>
                                <input type="text" class="form-control" id="add_volume_sold" name="volume_sold" inputmode="numeric">
                                <div class="text-danger error-message" id="error_add_volume_sold"></div>
                            </div>

                            <div class="form-group">
                                <label for="add_warranty_period">Bảo hành</label>
                                <input type="text" class="form-control" id="add_warranty_period"
                                    name="warranty_period" inputmode="numeric">
                                <div class="text-danger error-message" id="error_add_warranty_period"></div>
                            </div>

                            <div class="form-group">
                                <label for="add_embed_url_review">Link review</label>
                                <input type="text" class="form-control" id="add_embed_url_review"
                                    name="embed_url_review">
                                <div class="text-danger error-message" id="error_add_embed_url_review"></div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Thêm mới</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal Edit Product -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editProductForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="edit_updated_at" name="updated_at" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Cột trái -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_product_name">Tên sản phẩm</label>
                                <input type="text" class="form-control" id="edit_product_name" name="product_name">
                                <div class="text-danger error-message" id="error_edit_product_name"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_description">Mô tả</label>
                                <textarea class="form-control" id="edit_description" name="description"></textarea>
                                <div class="text-danger error-message" id="error_edit_product_description"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_stock_quantity">Số lượng tồn</label>
                                <input type="text" class="form-control" id="edit_stock_quantity"
                                    name="stock_quantity" inputmode="numeric">
                                <div class="text-danger error-message" id="error_edit_stock_quantity"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_release_date">Ngày phát hành</label>
                                <input type="date" class="form-control" id="edit_release_date" name="release_date">
                                <div class="text-danger error-message" id="error_edit_release_date"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_supplier_id">Nhà cung cấp</label>
                                <select class="form-control" id="edit_supplier_id" name="supplier_id">
                                    <option value="">-- Chọn nhà cung cấp --</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger error-message" id="error_edit_supplier_id"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_category_id">Danh mục</label>
                                <select class="form-control" id="edit_category_id" name="category_id">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger error-message" id="error_edit_category_id"></div>
                            </div>

                        </div>

                        <!-- Cột phải -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_cover_image">Hình ảnh</label>
                                <div class="mt-2 text-center">
                                    <img id="preview_image" src="" alt="Ảnh sản phẩm"
                                        style="max-width: 120px; border-radius: 6px;">
                                </div>
                                <input type="file" class="form-control" id="edit_cover_image" name="cover_image"
                                    accept="image/*">
                                <div class="text-danger error-message" id="error_edit_cover_image"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_price">Giá</label>
                                <input type="text" class="form-control" id="edit_price" name="price" inputmode="decimal">
                                <div class="text-danger error-message" id="error_edit_price"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_volume_sold">Đã bán</label>
                                <input type="text" class="form-control" id="edit_volume_sold" name="volume_sold" inputmode="numeric">
                                <div class="text-danger error-message" id="error_edit_volume_sold"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_warranty_period">Bảo hành</label>
                                <input type="text" class="form-control" id="edit_warranty_period"
                                    name="warranty_period" inputmode="numeric">
                                <div class="text-danger error-message" id="error_edit_warranty_period"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_embed_url_review">Link review</label>
                                <input type="text" class="form-control" id="edit_embed_url_review"
                                    name="embed_url_review">
                                <div class="text-danger error-message" id="error_edit_embed_url_review"></div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script src="js/crud-product.js"></script>
@endpush
@endsection