@extends('layouts.dashboard')

@section('content')
    <!-- Main Content -->
    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @livewire('category-table')
        </div>
    </main>


    <!-- Modal Edit category -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Chỉnh sửa danh mục</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="category_name">Tên danh mục</label>
                                    <input type="text" class="form-control" id="category_name" name="category_name">
                                    <div class="text-danger error-message" id="error_edit_category_name"></div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_cover_image">Hình ảnh danh mục</label>
                                    <div class="mt-2 text-center">
                                        <img id="preview_image" src="" alt="Ảnh sản phẩm"
                                            style="max-width: 120px; border-radius: 6px;">
                                    </div>
                                    <input type="file" class="form-control" id="edit_cover_image" name="cover_image"
                                        accept="image/*">
                                    <div class="text-danger error-message" id="error_edit_cover_image"></div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                    <div class="text-danger error-message" id="error_edit_description"></div>
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

    <!-- Modal Thêm Mới danh mục -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="addCategoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Thêm danh mục mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="add_product_name">Tên danh mục</label>
                                    <input type="text" class="form-control" id="add_category_name" name="category_name">
                                    <div class="text-danger error-message" id="error_add_category_name"></div>
                                </div>
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
                                    <label for="add_description">Mô tả</label>
                                    <textarea class="form-control" id="add_description" name="description"></textarea>
                                    <div class="text-danger error-message" id="error_add_description"></div>
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


@endsection

@push('scripts')
    <script src="js/crud-categories.js">

    </script>
@endpush