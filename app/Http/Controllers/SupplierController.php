<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class SupplierController extends Controller
{

    // Hiển thị danh sách nhà cung cấp

    public function list()
    {
        $query = Supplier::query();

        // Nếu có tham số tìm kiếm
        if (request()->has('search') && request('search')) {
            $query = Supplier::searchByName(request('search'));
        }

        // Lọc theo địa chỉ nếu có
        if (request()->has('address_filter') && request('address_filter')) {
            $query->where('address', request('address_filter'));
        }

        // Lấy danh sách địa chỉ duy nhất để hiển thị trong dropdown
        $allAddresses = Supplier::select('address')
            ->whereNotNull('address')
            ->distinct()
            ->pluck('address');

        $suppliers = $query->paginate(5);

        return view('crud_suppliers.list', compact('suppliers', 'allAddresses'));
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
        $validated = $request->validated();
        $supplier = Supplier::updateSupplier($id, $validated);

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

        } catch (\Exception $e) {
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
     * Chuyển đổi (transform) 1 sản phẩm sang định dạng JSON
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
     * HÀM XỬ LÝ API CHÍNH - (ĐÃ CẬP NHẬT LẦN CUỐI)
     */
    private function handleSupplierApi($id, $sortType = 'default')
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $perPage = 6;

            // 1. Tạo Query cơ bản
            $query = $supplier->products()
                ->with([
                    'discounts' => function ($q) {
                        $q->active(); // Eager load vẫn hoạt động!
                    },
                    // Quan trọng: Tải luôn supplier, vì query gốc là từ $supplier->products()
                    // Điều này đảm bảo $product->supplier->name luôn tồn tại
                    'supplier'
                ]);

            // 2. Áp dụng Sắp Xếp (Sort)
            $now = now()->toDateTimeString(); // Chuyển sang string để dùng trong Raw Query

            switch ($sortType) {

                case 'newest':
                    $query->orderBy('products.created_at', 'desc');
                    break;

                case 'price_asc':
                case 'price_desc':
                    $direction = ($sortType == 'price_asc') ? 'asc' : 'desc';

                    // Sắp xếp bằng giá-sau-giảm (dùng subquery)
                    $query->orderByRaw(
                        "COALESCE(
                            (SELECT sale_price FROM product_discounts 
                             WHERE product_discounts.product_id = products.product_id 
                             AND (product_discounts.start_date IS NULL OR product_discounts.start_date <= '$now')
                             AND (product_discounts.end_date IS NULL OR product_discounts.end_date >= '$now')
                             ORDER BY sale_price ASC 
                             LIMIT 1), 
                            products.price
                        ) $direction"
                    );
                    break;

                case 'best_seller':
                    $query->withCount('orderDetails as sales_count')
                        ->orderBy('sales_count', 'desc');
                    break;

                case 'best_discount':
                    // Sắp xếp bằng % giảm giá (dùng subquery)
                    $query->orderByRaw(
                        "COALESCE(
                            (SELECT discount_percent FROM product_discounts 
                             WHERE product_discounts.product_id = products.product_id 
                             AND (product_discounts.start_date IS NULL OR product_discounts.start_date <= '$now')
                             AND (product_discounts.end_date IS NULL OR product_discounts.end_date >= '$now')
                             ORDER BY discount_percent DESC
                             LIMIT 1), 
                            0
                        ) DESC"
                    );
                    break;

                case 'default':
                default:
                    $query->orderBy('products.created_at', 'desc');
                    break;
            }
            $cartItemCount = 0;

            if (Auth::check()) {
                $cartItemCount = CartItem::where('user_id', Auth::id())->count('quantity');
            }
            // 3. Phân trang (Paginate)
            // Không cần 'groupBy' nữa vì chúng ta không Join
            $products = $query->paginate($perPage);

            // 4. Transform collection
            // Bỏ 'setRelation' vì Eager Loading đã hoạt động
            $productItems = $products->getCollection()->map(function ($product) {
                // Giờ $product->discounts và $product->supplier đều có
                return $this->transformProduct($product);
            });

            // 5. Chuẩn bị Supplier Data (chỉ cho trang 1)
            $supplierData = null;
            if ($products->currentPage() == 1) {
                $supplierData = $this->getSupplierData($supplier, $products->total());
            }

            // 6. Trả về JSON
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

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage() . ' tại file ' . $e->getFile() . ' dòng ' . $e->getLine() // Lấy lỗi chi tiết
            ], 500);
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

        // $supplier bây giờ sẽ có cả 'completed_orders' (giả sử có sẵn) 
        // và 'total_products_sold'

        return response()->json($supplier);
    }
}


