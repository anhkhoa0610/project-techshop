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
                                <button class="btn btn-info add-new">Thêm chi tiết đơn hàng mới
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý chi tiết đơn hàng Order ID: <?php echo ($order_id) ?></b>
                                </h2>
                            </div>
                            <div class="col-sm-4">
                                <div class="search-box">
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
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product name</th>
                                <th>Product image</th>
                                <th>Quantity</th>
                                <th>Unit price</th>
                                <th>Total price</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderDetails as $detail)
                                <tr data-detail-id="{{ $detail->order_detail_id }}" data-order-id="{{ $detail->order_id }}"
                                    data-product-id="{{ $detail->product_id }}"
                                    data-product-name="{{ $detail->product->product_name }}"
                                    data-product-image="{{ $detail->product->cover_image }}"
                                    data-quantity="{{ $detail->quantity }}" data-unit-price="{{ $detail->unit_price }}"
                                    data-total-price="{{ number_format(($detail->unit_price) * ($detail->quantity), 2) }}">
                                    <td>{{ $detail->order_detail_id }}</td>
                                    <td>{{ $detail->product->product_name }}</td>
                                    <td><img src="{{ asset('uploads/' . $detail->product->cover_image) }}"
                                            alt="{{ $detail->product->product_name }}" width="50"></td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->unit_price, 0, ',', '.') }} ₫</td>
                                    <td>{{ number_format(($detail->unit_price) * ($detail->quantity), 0,',','.') }}₫</td>

                                    <td>
                                        <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                                class="material-icons">&#xE417;</i></a>
                                        <a href="#" class="edit" title="Edit" data-toggle="tooltip"><i
                                                class="material-icons">&#xE254;</i></a>
                                        <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i
                                                class="material-icons">&#xE872;</i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="clearfix">
                            <nav>
                                {{ $orderDetails->withQueryString()->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm orderdetail -->
    <div class="modal fade" id="addOrderDetailModal" tabindex="-1" role="dialog" aria-labelledby="addOrderDetailModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="addOrderDetailForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOrderDetailModal">Thêm chi tiết đơn hàng mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" id="add_order_id" name="order_id" value="{{ $order_id }}">
                                </div>
                                <div class="form-group">
                                    <label for="add_description">product name</label>
                                    <select class="form-control" id="add_product_id" name="product_id">
                                        <option value="{{ null }}">-- chọn sản phẩm --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->product_id }}">
                                                {{ $product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_add_product_id"></div>
                                </div>
                                <div class="form-group">
                                    <label for="add_quantity">quantity</label>
                                    <input type="number" class="form-control" id="add_quantity" name="quantity"
                                        placeholder="Nhập số lượng">
                                    <div class="text-danger error-message" id="error_add_quantity"></div>
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

    <!-- Modal sửa orderdetail -->
    <div class="modal fade" id="editOrderDetailModal" tabindex="-1" role="dialog" aria-labelledby="editOrderDetailModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editOrderDetailForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderDetailModal">Sửa chi tiết đơn hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" id="edit_order_id" name="order_id" value="{{ $order_id }}">
                                </div>
                                <div class="form-group">
                                    <label for="add_description">product name</label>
                                    <select class="form-control" id="edit_product_id" name="product_id">
                                        <option value="{{ null }}">-- chọn sản phẩm --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->product_id }}">
                                                {{ $product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_product_id"></div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_quantity">quantity</label>
                                    <input type="number" class="form-control" id="edit_quantity" name="quantity"
                                        placeholder="Nhập số lượng">
                                    <div class="text-danger error-message" id="error_edit_quantity"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal View orderDetail -->
    <div class="modal fade" id="viewOrderDetailModal" tabindex="-1" role="dialog" aria-labelledby="viewOrderDetailLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewSupplierModalLabel">
                        Thông tin chi tiết đơn hàng
                    </h5>
                </div>
                <div class="modal-body bg-light">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <img id="view_product_image" src="" alt="Hình ảnh sản phẩm" class="img-thumbnail shadow"
                                    style="max-height: 220px; background: #fff;">
                            </div>
                            <h4 id="view_name" class="font-weight-bold text-secondary mb-2"></h4>
                        </div>
                        <div class="col-md-8">
                            <div class="card border-0 bg-white shadow-sm">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">order detail ID:</div>
                                        <div class="col-8" id="view_stock_quantity"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Tên sản phẩm:</div>
                                        <div class="col-8" id="view_product_name"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Số lượng đã mua:</div>
                                        <div class="col-8" id="view_quantity"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 font-weight-bold text-secondary">Đơn giá :</div>
                                        <div class="col-8" id="view_unit_price"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 font-weight-bold text-secondary">Tổng giá:</div>
                                        <div class="col-8" id="view_total_price"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="material-icons align-middle">close</i> Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
    </div>
    </div>

    <script>
        // Hiển thị modal khi nhấn nút "Chỉnh sửa" chi tiết đơn hàng
        document.querySelectorAll('.edit').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                // Xóa lỗi cũ
                document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

                // Lấy dòng chứa nút "edit" được bấm
                const row = btn.closest('tr');

                // Gán dữ liệu vào form trong modal
                document.getElementById('edit_product_id').value = row.getAttribute('data-product-id') || '';
                document.getElementById('edit_quantity').value = row.getAttribute('data-quantity') || '';
                document.getElementById('edit_order_id').value = row.getAttribute('data-order-id') || '';

                // Lưu ID của order_detail để gửi PUT khi submit
                document.getElementById('editOrderDetailForm').dataset.id = row.getAttribute('data-detail-id');

                // Hiển thị modal
                $('#editOrderDetailModal').modal('show');
            });
        });


        // xử lý submit form chỉnh sửa
        document.getElementById('editOrderDetailForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const detailId = this.dataset.id; // id gán khi mở modal
            const url = `/api/orderDetails/${detailId}`;

            const formData = new FormData(this);
            formData.append('_method', 'PUT'); // Laravel nhận update()

            try {
                const response = await fetch(url, {
                    method: 'POST', // vẫn là POST, nhưng Laravel đọc _method = PUT
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cập nhật chi tiết đơn hàng thành công!',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    }).then(() => location.reload());

                    $('#editOrderDetailModal').modal('hide');
                } else {
                    const err = await response.json();
                    if (err.errors) {
                        // Xóa lỗi cũ
                        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
                        // Hiển thị lỗi mới
                        Object.keys(err.errors).forEach(field => {
                            const errorDiv = document.getElementById(`error_edit_${field}`);
                            if (errorDiv) {
                                errorDiv.textContent = err.errors[field][0];
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cập nhật thất bại',
                            text: 'Đã xảy ra lỗi không xác định',
                            confirmButtonText: 'Đóng',
                            confirmButtonColor: '#d33'
                        });
                    }
                }
            } catch (error) {
                console.error('Lỗi kết nối:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối máy chủ',
                    text: 'Không thể gửi yêu cầu cập nhật',
                    confirmButtonText: 'Đóng'
                });
            }
        });

        // Hiển thị modal khi nhấn nút "Thêm mới orderdetail"
        document.querySelector('.add-new').addEventListener('click', function () {
            // Reset form
            // Xóa lỗi cũ
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            $('#addOrderDetailModal').modal('show');
        });

        // Xử lý submit form thêm mới danh mục
        document.getElementById('addOrderDetailForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const url = '/api/orderDetails';
            const formData = new FormData(this);
            // Xóa lỗi cũ
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thêm chi tiết đơn hàng thành công!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    location.reload();
                });
                $('#addOrderDetailModal').modal('hide');

            } else {
                const err = await response.json();
                if (err.errors) {
                    Object.keys(err.errors).forEach(field => {
                        const errorDiv = document.getElementById(`error_add_${field}`);
                        if (errorDiv) {
                            errorDiv.textContent = err.errors[field][0];
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thêm chi tiết đơn hàng thất bại',
                        text: 'Lỗi không xác định',
                        confirmButtonText: 'Đóng',
                        confirmButtonColor: '#d33'
                    });
                }
            }
        });

        // Hiển thị modal khi nhấn nút "Xem" chi tiết đơn hàng
        document.querySelectorAll('.view').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();

                const row = btn.closest('tr');
                document.getElementById('view_product_image').src = row.getAttribute('data-product-image') ? '/uploads/' + row.getAttribute('data-product-image') : '/uploads/place-holder.jpg';
                document.getElementById('view_stock_quantity').textContent = row.getAttribute('data-detail-id') || '';
                document.getElementById('view_product_name').textContent = row.getAttribute('data-product-name') || '';
                document.getElementById('view_quantity').textContent = row.getAttribute('data-quantity') || '';
                document.getElementById('view_unit_price').textContent = formatCurrency(row.getAttribute('data-unit-price')) || '';
                document.getElementById('view_total_price').textContent = formatCurrency(row.getAttribute('data-total-price')) || '';

                // Hiển thị modal
                $('#viewOrderDetailModal').modal('show');
            });
        });
        function formatCurrency(value) {
            const number = parseFloat(value);
            if (isNaN(number)) return '0 ₫';
            return number.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
        }


    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection