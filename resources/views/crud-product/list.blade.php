@extends('layouts.dashboard')
<style>

</style>

@section('content')
    <!-- Main Content -->
    <div class="content">
        <div class="container-xl">
            <div class="table-responsive text-center">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-4">
                                <button class="btn btn-info add-new">Thêm Mới Sản Phẩm
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý Sản Phẩm</b></h2>
                            </div>
                            <div class="col-sm-4">
                                <form class="search-box" method="GET" action="{{ url()->current() }}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="material-icons">&#xE8B6;</i></span>
                                        <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..."
                                            value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stock Quantity</th>
                                <th>Supplier</th>
                                <th>Category</th>
                                <th>Warranty</th>
                                <th>Sold</th>
                                <th>Release Date</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr data-product-id="{{ $product->product_id }}"
                                    data-product-name="{{ $product->product_name }}"
                                    data-description="{{ $product->description }}"
                                    data-cover-image="{{ $product->cover_image }}" data-price="{{ $product->price }}"
                                    data-stock-quantity="{{ $product->stock_quantity }}"
                                    data-supplier-id="{{ $product->supplier_id }}"
                                    data-category-id="{{ $product->category_id }}"
                                    data-warranty-period="{{ $product->warranty_period }}"
                                    data-volume-sold="{{ $product->volume_sold }}"
                                    data-release-date="{{ $product->release_date }}">
                                    <td>{{ $product->product_id }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>
                                        @if($product->cover_image)
                                            <img src="{{ asset('uploads/' . $product->cover_image) }}"
                                                alt="{{ $product->product_name }}" style="max-width: 100px;">
                                        @else
                                            <img src="{{ asset('images/place-holder.jpg') }}" alt="place-holder"
                                                style="max-width: 100px;">
                                        @endif
                                    </td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->stock_quantity }}</td>
                                    <td>{{ $product->category->category_name ?? '—' }}</td>
                                    <td>{{ $product->supplier->name ?? '—' }}</td>
                                    <td>{{ $product->warranty_period }}</td>
                                    <td>{{ $product->volume_sold }}</td>
                                    <td>{{ $product->release_date }}</td>
                                    <td>
                                        <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                                class="material-icons">&#xE417;</i></a>
                                        <a href="#" class="edit" title="Edit" data-toggle="modal"
                                            data-target="#editProductModal">
                                            <i class="material-icons">&#xE254;</i>
                                        </a>
                                        <form action="{{ url('/api/products/' . $product->product_id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline delete"
                                                title="Delete" data-toggle="tooltip"
                                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                                                <i class="material-icons text-danger">&#xE872;</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="clearfix">
                            <nav>
                                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Product -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
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
                                    <label for="product_name">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="stock_quantity">Số lượng tồn</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="release_date">Ngày phát hành</label>
                                    <input type="date" class="form-control" id="release_date" name="release_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="supplier_id">Nhà cung cấp</label>
                                    <select class="form-control" id="supplier_id" name="supplier_id" required>
                                        <option value="">-- Chọn nhà cung cấp --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category_id">Danh mục</label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cover_image">Hình ảnh</label>
                                    <div class="mt-2 text-center">
                                        <img id="preview_image" src="" alt="Ảnh sản phẩm"
                                            style="max-width: 120px; border-radius: 6px;">
                                    </div>
                                    <input type="file" class="form-control" id="cover_image" name="cover_image"
                                        accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label for="price">Giá</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                                </div>
                                <div class="form-group">
                                    <label for="volume_sold">Đã bán</label>
                                    <input type="number" class="form-control" id="volume_sold" name="volume_sold" required>
                                </div>
                                <div class="form-group">
                                    <label for="warranty_period">Bảo hành</label>
                                    <input type="number" class="form-control" id="warranty_period" name="warranty_period"
                                        required>
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
                                    <input type="text" class="form-control" id="add_product_name" name="product_name"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="add_description">Mô tả</label>
                                    <textarea class="form-control" id="add_description" name="description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="add_stock_quantity">Số lượng tồn</label>
                                    <input type="number" class="form-control" id="add_stock_quantity" name="stock_quantity"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="add_release_date">Ngày phát hành</label>
                                    <input type="date" class="form-control" id="add_release_date" name="release_date"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="add_supplier_id">Nhà cung cấp</label>
                                    <select class="form-control" id="add_supplier_id" name="supplier_id" required>
                                        <option value="">-- Chọn nhà cung cấp --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="add_category_id">Danh mục</label>
                                    <select class="form-control" id="add_category_id" name="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
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
                                </div>
                                <div class="form-group">
                                    <label for="add_price">Giá</label>
                                    <input type="number" step="0.01" class="form-control" id="add_price" name="price"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="add_volume_sold">Đã bán</label>
                                    <input type="number" class="form-control" id="add_volume_sold" name="volume_sold"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="add_warranty_period">Bảo hành</label>
                                    <input type="number" class="form-control" id="add_warranty_period"
                                        name="warranty_period" required>
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

    <script>
        document.querySelectorAll('.edit').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var row = btn.closest('tr');

                document.getElementById('product_name').value = row.getAttribute('data-product-name') || '';
                document.getElementById('description').value = row.getAttribute('data-description') || '';
                document.getElementById('price').value = row.getAttribute('data-price') || '';
                document.getElementById('stock_quantity').value = row.getAttribute('data-stock-quantity') || '';
                document.getElementById('supplier_id').value = row.getAttribute('data-supplier-id') || '';
                document.getElementById('category_id').value = row.getAttribute('data-category-id') || '';
                document.getElementById('warranty_period').value = row.getAttribute('data-warranty-period') || '';
                document.getElementById('volume_sold').value = row.getAttribute('data-volume-sold') || '';
                document.getElementById('release_date').value = row.getAttribute('data-release-date') || '';

                const imageFile = row.getAttribute('data-cover-image');
                const preview = document.getElementById('preview_image');
                if (imageFile) {
                    preview.src = `/uploads/${imageFile}`;
                } else {
                    preview.src = `/images/place-holder.jpg`;
                }

                // Reset input file
                document.getElementById('cover_image').value = '';

                document.getElementById('editProductForm').dataset.id = row.getAttribute('data-product-id');

                $('#editProductModal').modal('show');
            });
        });

        document.getElementById('cover_image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview_image');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Xử lý submit form
        document.getElementById('editProductForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const id = this.dataset.id;
            const url = `/api/products/${id}`;

            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('product_name', document.getElementById('product_name').value);
            formData.append('description', document.getElementById('description').value);
            formData.append('stock_quantity', document.getElementById('stock_quantity').value);
            formData.append('price', document.getElementById('price').value);
            formData.append('volume_sold', document.getElementById('volume_sold').value);
            formData.append('category_id', document.getElementById('category_id').value);
            formData.append('supplier_id', document.getElementById('supplier_id').value);
            formData.append('warranty_period', document.getElementById('warranty_period').value);
            formData.append('release_date', document.getElementById('release_date').value);
            const fileInput = document.getElementById('cover_image');
            if (fileInput.files.length > 0) {
                formData.append('cover_image', fileInput.files[0]);
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            if (response.ok) {
                alert('Cập nhật sản phẩm thành công!');
                $('#editProductModal').modal('hide');
                location.reload();
            } else {
                const err = await response.json();

                console.error(err);
                alert('Cập nhật thất bại: ' + (err.message || 'Lỗi không xác định'));
            }
        });

        // Hiển thị modal khi nhấn nút "Thêm Mới Sản Phẩm"
        document.querySelector('.add-new').addEventListener('click', function () {
            // Reset form
            document.getElementById('addProductForm').reset();
            document.getElementById('add_preview_image').src = "{{ asset('images/place-holder.jpg') }}";
            $('#addProductModal').modal('show');
        });

        // Xem trước ảnh khi chọn file
        document.getElementById('add_cover_image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('add_preview_image');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Xử lý submit form thêm mới
        document.getElementById('addProductForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const url = '/api/products';
            const formData = new FormData(this);

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            if (response.ok) {
                alert('Thêm sản phẩm thành công!');
                $('#addProductModal').modal('hide');
                location.reload();
            } else {
                const err = await response.json();
                alert('Thêm thất bại: ' + (err.message || 'Lỗi không xác định'));
            }
        });
    </script>
@endsection