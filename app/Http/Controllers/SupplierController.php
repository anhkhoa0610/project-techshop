<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Exception;

class SupplierController extends Controller
{

    // Hiển thị danh sách nhà cung cấp

    public function list(Request $request)
    {
        // Kiểm tra page hợp lệ
        $page = $request->query('page', 1);
        if (!ctype_digit((string) $page) || $page < 1) {
            return redirect()->route('supplier.list', ['page' => 1]);
        }

        // Phân trang
        $suppliers = Supplier::paginate(5);

        $lastPage = $suppliers->lastPage();

        if ($page > $lastPage && $lastPage != 0) {
            return redirect()->route('supplier.list', ['page' => $lastPage]);
        }

        return view('crud_suppliers.list', compact('suppliers'));
    }

    public function index()
    {
        $suppliers = Supplier::all();
        return response()->json([
            'success' => true,
            'data' => $suppliers,
            'message' => 'Lấy danh sách nhà cung cấp thành công.',
        ]);
    }

    public function indexView($id)
    {
        //Truyền $id vào view với tên là 'supplier_id'
        return view('ui-supplier.supplier', [
            'supplier_id' => $id
        ]);
    }

    // Lưu nhà cung cấp mới
    public function store(SupplierRequest $request)
    {
        $validated = $request->validated();
        $supplier = Supplier::createSupplier($validated);

        return response()->json([
            'success' => true,
            'data' => $supplier,
            'message' => 'Tạo nhà cung cấp mới thành công.',
        ]);
    }

    public function update(SupplierRequest $request, $id)
    {
        // 1. Validate dữ liệu
        $validated = $request->validated();

        // 2. Tìm bản ghi (Dùng lockForUpdate nếu muốn chặt chẽ hơn, nhưng ở đây dùng Optimistic Locking)
        $supplier = Supplier::findOrFail($id);

        // 3. CHECK CONFLICT (Optimistic Locking)
        // Chuyển cả 2 về string chuẩn 'Y-m-d H:i:s' để so sánh chính xác
        $dbUpdatedTime = $supplier->updated_at->format('Y-m-d H:i:s');
        $clientUpdatedTime = \Carbon\Carbon::parse($request->original_updated_at)->format('Y-m-d H:i:s');

        if ($dbUpdatedTime !== $clientUpdatedTime) {
            return response()->json([
                'message' => 'Dữ liệu đã bị thay đổi bởi người khác. Vui lòng tải lại trang.',
                'debug_db' => $dbUpdatedTime, // Debug để xem lệch giờ thế nào (nếu cần)
                'debug_client' => $clientUpdatedTime
            ], 409);
        }

        // 4. Update
        $supplier->update($validated);

        return response()->json([
            'success' => true,
            'data' => $supplier,
            'message' => 'Cập nhật nhà cung cấp thành công.',
        ]);
    }

    public function destroy($id)
    {
        try {
            Supplier::deleteSupplier($id);
            return response()->json([
                'success' => true,
                'message' => 'Xóa nhà cung cấp thành công.',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa nhà cung cấp: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lấy thông tin cơ bản của Supplier (dùng cho tất cả các API)
     */
    private function getSupplierData($supplier, $totalProducts)
    {
        return [
            'id' => $supplier->supplier_id,
            'name' => $supplier->name,
            'email' => $supplier->email,
            'phone' => $supplier->phone,
            'address' => $supplier->address,
            'description' => $supplier->description,
            'logo_url' => $supplier->logo ? asset('uploads/' . $supplier->logo) : asset('placeholder-logo.png'),
            'product_count' => $totalProducts, // Truyền tổng số sản phẩm vào
            'join_date' => $supplier->created_at->diffForHumans(['parts' => 1, 'short' => true]),
            'total_products_sold' => $supplier->total_products_sold,
        ];
    }

    /**
     * Chuyển đổi 1 sản phẩm sang định dạng JSON
     */
    private function transformProduct($product)
    {
        $discount = $product->discounts->first(); // Eager-loaded
        $originalPrice = (float) ($product->price ?? 0);
        $salePrice = (float) ($discount ? ($discount->sale_price ?? $originalPrice) : $originalPrice);
        $discountAmount = max(0, $originalPrice - $salePrice);

        return [
            'supplier_name' => $product->supplier->name,
            'product_id' => $product->product_id,
            'name' => $product->product_name,
            'price' => $originalPrice,
            'sale_price' => $salePrice,
            'discount_amount' => $discountAmount,
            'discount' => $discount ? [
                'discount_percent' => $discount->discount_percent ?? 0,
                'start_date' => $discount->start_date,
                'end_date' => $discount->end_date,
            ] : null,
            'image' => $product->cover_image ? asset('uploads/' . $product->cover_image) : asset('placeholder.png'),
            'sales_count' => (int) ($product->sales_count ?? 0), // Dùng cho sort 'bestseller'
            // 'effective_price' => $salePrice, // Không cần gửi, chỉ dùng để sort
            'stock_quantity' => (int) ($product->stock_quantity ?? 0),
        ];
    }

    /**
     * HÀM XỬ LÝ API CHÍNH CHO NHÀ CUNG CẤP
     */
    private function handleSupplierApi($id, $sortType = 'default')
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // --- GỌI HÀM TỪ MODEL ---
            $products = $supplier->getProductsForApi($sortType, 6);

            // --- CÁC PHẦN XỬ LÝ KHÁC (Cart, Transform...) GIỮ NGUYÊN ---
            $cartItemCount = Auth::check() ? CartItem::where('user_id', Auth::id())->sum('quantity') : 0;

            $productItems = $products->getCollection()->map(function ($product) {
                return $this->transformProduct($product);
            });

            $supplierData = ($products->currentPage() == 1)
                ? $this->getSupplierData($supplier, $products->total())
                : null;

            return response()->json([
                'success' => true,
                'cartItemCount' => $cartItemCount,
                'supplier' => $supplierData,
                'products' => $productItems,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'has_more_pages' => $products->hasMorePages(),
                ],
            ]);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }

    // ----- CÁC HÀM PUBLIC API CỦA BẠN -----
    // Giờ đây chúng chỉ cần gọi hàm 'handleSupplierApi'

    public function showApi($id)
    {
        return $this->handleSupplierApi($id, 'default');
    }

    public function sortBestproductDiscount($id)
    {
        return $this->handleSupplierApi($id, 'best_discount');
    }

    public function sortpriceascProduct($id)
    {
        return $this->handleSupplierApi($id, 'price_asc');
    }

    public function sortpricedescProduct($id)
    {
        return $this->handleSupplierApi($id, 'price_desc');
    }

    public function sortnewestProduct($id)
    {
        return $this->handleSupplierApi($id, 'newest');
    }

    public function sortbestsellerProduct($id)
    {
        return $this->handleSupplierApi($id, 'best_seller');
    }

    public function getSupplierDetails($id)
    {
        $supplier = Supplier::findOrFail($id);

        return response()->json($supplier);
    }
}


