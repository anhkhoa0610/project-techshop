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
                            <select name="sort_by" class="form-select"
                                style="max-width: 160px; margin-left: 15px; margin-top: 15px;">
                                <option value="">Sắp xếp theo...</option>
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên sản phẩm
                                </option>
                                <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Giá</option>
                                <option value="stock_quantity" {{ request('sort_by') == 'stock_quantity' ? 'selected' : '' }}>
                                    Số lượng tồn</option>
                                <option value="release_date" {{ request('sort_by') == 'release_date' ? 'selected' : '' }}>Ngày
                                    phát hành</option>
                            </select>
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
                                    data-release-date="{{ $product->release_date }}"
                                    data-category-name="{{ $product->category->category_name ?? '' }}"
                                    data-supplier-name="{{ $product->supplier->name ?? '' }}"
                                    data-embed-url-review="{{ $product->embed_url_review ?? '' }}">
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
                                    <td>{{ number_format($product->price, 0) }}</td>
                                    <td>{{ $product->stock_quantity }}</td>
                                    <td>{{ $product->supplier->name ?? '—' }}</td>
                                    <td>{{ $product->category->category_name ?? '—' }}</td>
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
                                        <button type="button" class="btn btn-link p-0 m-0 align-baseline delete" title="Delete"
                                            data-toggle="tooltip" onclick="confirmDelete({{ $product->product_id }})">
                                            <i class="material-icons text-danger">&#xE872;</i>
                                        </button>
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
                                    <input type="text" class="form-control" id="product_name" name="product_name">
                                    <div class="text-danger error-message" id="error_edit_product_name"></div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                    <div class="text-danger error-message" id="error_edit_product_description"></div>
                                </div>

                                <div class="form-group">
                                    <label for="stock_quantity">Số lượng tồn</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity">
                                    <div class="text-danger error-message" id="error_edit_stock_quantity"></div>
                                </div>

                                <div class="form-group">
                                    <label for="release_date">Ngày phát hành</label>
                                    <input type="date" class="form-control" id="release_date" name="release_date">
                                    <div class="text-danger error-message" id="error_edit_release_date"></div>
                                </div>

                                <div class="form-group">
                                    <label for="supplier_id">Nhà cung cấp</label>
                                    <select class="form-control" id="supplier_id" name="supplier_id">
                                        <option value="">-- Chọn nhà cung cấp --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_supplier_id"></div>
                                </div>

                                <div class="form-group">
                                    <label for="category_id">Danh mục</label>
                                    <select class="form-control" id="category_id" name="category_id">
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
                                    <label for="cover_image">Hình ảnh</label>
                                    <div class="mt-2 text-center">
                                        <img id="preview_image" src="" alt="Ảnh sản phẩm"
                                            style="max-width: 120px; border-radius: 6px;">
                                    </div>
                                    <input type="file" class="form-control" id="cover_image" name="cover_image"
                                        accept="image/*">
                                    <div class="text-danger error-message" id="error_edit_cover_image"></div>
                                </div>

                                <div class="form-group">
                                    <label for="price">Giá</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price">
                                    <div class="text-danger error-message" id="error_edit_price"></div>
                                </div>

                                <div class="form-group">
                                    <label for="volume_sold">Đã bán</label>
                                    <input type="number" class="form-control" id="volume_sold" name="volume_sold">
                                    <div class="text-danger error-message" id="error_edit_volume_sold"></div>
                                </div>

                                <div class="form-group">
                                    <label for="warranty_period">Bảo hành</label>
                                    <input type="number" class="form-control" id="warranty_period" name="warranty_period">
                                    <div class="text-danger error-message" id="error_edit_warranty_period"></div>
                                </div>

                                <div class="form-group">
                                    <label for="embed_url_review">Link review</label>
                                    <input type="text" class="form-control" id="embed_url_review" name="embed_url_review">
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
                                    <input type="number" class="form-control" id="add_stock_quantity" name="stock_quantity">
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
                                    <input type="number" step="0.01" class="form-control" id="add_price" name="price">
                                    <div class="text-danger error-message" id="error_add_price"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_volume_sold">Đã bán</label>
                                    <input type="number" class="form-control" id="add_volume_sold" name="volume_sold">
                                    <div class="text-danger error-message" id="error_add_volume_sold"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_warranty_period">Bảo hành</label>
                                    <input type="number" class="form-control" id="add_warranty_period"
                                        name="warranty_period">
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

    <!-- Modal View Product -->
    <div class="modal fade" id="viewProductModal" tabindex="-1" role="dialog" aria-labelledby="viewProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="viewProductModalLabel">
                        Thông tin Sản Phẩm
                    </h5>
                </div>
                <div class="modal-body bg-light">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <img id="view_product_image" src="" alt="Ảnh sản phẩm" class="img-thumbnail shadow"
                                    style="max-height: 120px; background: #fff;">
                            </div>
                            <h4 id="view_product_name" class="font-weight-bold text-secondary mb-2"></h4>
                            <div id="view_category"
                                class="font-weight-bold mb-1 rounded px-2 py-1 bg-info text-dark d-inline-block"></div>
                            <div id="view_supplier"
                                class="font-weight-bold mb-1 rounded px-2 py-1 bg-info text-dark d-inline-block"></div>
                        </div>
                        <div class="col-md-8">
                            <div class="card border-0 bg-white shadow-sm">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Giá:</div>
                                        <div class="col-8" id="view_price"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Số lượng tồn:</div>
                                        <div class="col-8" id="view_stock_quantity"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Đã bán:</div>
                                        <div class="col-8" id="view_volume_sold"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Bảo hành:</div>
                                        <div class="col-8" id="view_warranty_period"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Ngày phát hành:</div>
                                        <div class="col-8" id="view_release_date"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 font-weight-bold text-secondary">Mô tả:</div>
                                        <div class="col-8" id="view_description"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4 font-weight-bold text-secondary">Link Review</div>
                                        <div class="col-8" id="view_embed_url_review"></div>
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

    <script>
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/crud-product.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection