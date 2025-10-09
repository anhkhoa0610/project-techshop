@extends('layouts.dashboard')

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
                                <div class="search-box">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="material-icons">&#xE8B6;</i></span>
                                        <input type="text" class="form-control" placeholder="Search&hellip;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
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
                                <tr>
                                    <td>{{ $product->product_id }}</td>
                                    <td>{{ $product->product_name }}</td>
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
                                        <a href="#" class="edit" title="Edit" data-toggle="tooltip"><i
                                                class="material-icons">&#xE254;</i></a>
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
                        <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                        <ul class="pagination">
                            <li class="page-item disabled"><a href="#">Previous</a></li>
                            <li class="page-item"><a href="#" class="page-link">1</a></li>
                            <li class="page-item"><a href="#" class="page-link">2</a></li>
                            <li class="page-item active"><a href="#" class="page-link">3</a></li>
                            <li class="page-item"><a href="#" class="page-link">4</a></li>
                            <li class="page-item"><a href="#" class="page-link">5</a></li>
                            <li class="page-item"><a href="#" class="page-link">Next</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Product -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editProductForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Các trường chỉnh sửa sản phẩm -->
                        <div class="form-group">
                            <label for="product_name">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Giá</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="stock_quantity">Số lượng tồn</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
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
                        <!-- Thêm các trường khác nếu cần -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Bắt sự kiện click nút Edit
        document.querySelectorAll('.edit').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                // Lấy thông tin sản phẩm từ dòng hiện tại
                var row = btn.closest('tr');
                document.getElementById('product_name').value = row.children[1].textContent;
                document.getElementById('description').value = row.children[2].textContent;
                document.getElementById('price').value = row.children[3].textContent.replace(/,/g, '');
                document.getElementById('stock_quantity').value = row.children[4].textContent;
                // Cập nhật action cho form
                var productId = row.children[0].textContent;
                document.getElementById('editProductForm').action = '/api/products/' + productId;
                // Hiển thị modal
                $('#editProductModal').modal('show');
            });
        });
    </script>
    <!-- Kết thúc modal -->
    {{-- filepath: c:\Users\LAPTOP\Desktop\project-techshop\resources\views\crud-product\list.blade.php --}}
@endsection