<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;
use Illuminate\Pagination\LengthAwarePaginator;

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
            'message' => 'Suppliers retrieved successfully',
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
            'message' => 'Supplier created successfully',
        ]);
    }

    public function update(SupplierRequest $request, $id)
    {
        $validated = $request->validated();
        $supplier = Supplier::updateSupplier($id, $validated);

        return response()->json([
            'success' => true,
            'data' => $supplier,
            'message' => 'Supplier updated successfully',
        ]);
    }

    public function destroy($id)
    {
        try {
            Supplier::deleteSupplier($id);
            return response()->json([
                'success' => true,
                'message' => 'Supplier deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting supplier: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lấy dữ liệu của một nhà cung cấp qua API.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showApi($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // Lấy tất cả sản phẩm, eager-load discount (có hoặc không)
            $products = $supplier->products()
                ->with([
                    'discounts' => function ($query) {
                        $query->active();
                    }
                ])
                ->paginate(12);

            // Xây dựng dữ liệu trả về
            $supplierData = [
                'id' => $supplier->supplier_id,
                'name' => $supplier->name,
                'email' => $supplier->email,
                'phone' => $supplier->phone,
                'address' => $supplier->address,
                'description' => $supplier->description,
                'logo_url' => $supplier->logo ? asset('uploads/' . $supplier->logo) : asset('placeholder-logo.png'),
                'product_count' => $products->total(),
                'join_date' => $supplier->created_at->diffForHumans(['parts' => 1, 'short' => true]),
            ];

            // Transform products để thêm thông tin discount
            $productItems = $products->getCollection()->map(function ($product) {
                $discount = $product->discounts->first(); // Lấy discount còn hiệu lực đầu tiên (nếu có)
                $originalPrice = $product->price ?? 0;
                $salePrice = $discount ? ($discount->sale_price ?? $originalPrice) : $originalPrice;
                $discountAmount = $discount ? ($originalPrice - $salePrice) : 0;

                return [
                    'supplier_name' => $product->supplier->name,
                    'product_id' => $product->product_id,
                    'name' => $product->product_name,
                    'price' => $originalPrice,
                    'sale_price' => $salePrice,
                    'discount_amount' => $discountAmount, // Số tiền đã giảm
                    'discount' => $discount ? [
                        'discount_percent' => $discount->discount_percent ?? 0,
                        'start_date' => $discount->start_date,
                        'end_date' => $discount->end_date,
                    ] : null,
                    'image' => $product->cover_image ? asset('uploads/' . $product->cover_image) : asset('placeholder.png'),
                ];
            })->values()->all();

            return response()->json([
                'success' => true,
                'supplier' => $supplierData,
                'products' => $productItems,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found.'
            ], 404);
        }
    }

    public function sortBestproductDiscount($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // Lấy tất cả sản phẩm, eager-load discount còn hiệu lực
            $products = $supplier->products()
                ->with([
                    'discounts' => function ($query) {
                        $query->active();
                    }
                ])
                ->paginate(12);

            // Transform products và sắp xếp theo discount tốt nhất
            $productItems = $products->getCollection()->map(function ($product) {
                $discount = $product->discounts->first();
                $originalPrice = $product->price ?? 0;
                $salePrice = $discount ? ($discount->sale_price ?? $originalPrice) : $originalPrice;
                $discountAmount = $discount ? ($originalPrice - $salePrice) : 0;
                $discountPercent = $discount ? ($discount->discount_percent ?? 0) : 0;

                return [
                    'supplier_name' => $product->supplier->name,
                    'product_id' => $product->product_id,
                    'name' => $product->product_name,
                    'price' => $originalPrice,
                    'sale_price' => $salePrice,
                    'discount_amount' => $discountAmount,
                    'discount_percent' => $discountPercent,
                    'discount' => $discount ? [
                        'discount_percent' => $discountPercent,
                        'start_date' => $discount->start_date,
                        'end_date' => $discount->end_date,
                    ] : null,
                    'image' => $product->cover_image ? asset('uploads/' . $product->cover_image) : asset('placeholder.png'),
                ];
            })->sortByDesc(function ($item) {
                return [$item['discount_percent'], $item['discount_amount']];
            })->values()->all();

            return response()->json([
                'success' => true,
                'products' => $productItems,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found.'
            ], 404);
        }
    }
    public function sortpriceascProduct($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $perPage = 12;
            $page = (int) request()->get('page', 1);

            // Lấy tất cả sản phẩm và eager-load discount còn hiệu lực
            $products = $supplier->products()
                ->with([
                    'discounts' => function ($query) {
                        $query->active();
                    }
                ])->get();

            // Transform products, tính effective_price = sale_price nếu có, ngược lại price
            $productItems = $products->map(function ($product) {
                $discount = $product->discounts->first(); // Lấy discount còn hiệu lực đầu tiên (nếu có)
                $originalPrice = (float) ($product->price ?? 0);
                $salePrice = (float) ($discount ? ($discount->sale_price ?? $originalPrice) : $originalPrice);
                $discountAmount = max(0, $originalPrice - $salePrice);

                return [
                    'supplier_name' => $product->supplier->name,
                    'product_id' => $product->product_id,
                    'name' => $product->product_name,
                    'price' => $originalPrice,
                    'sale_price' => $salePrice,
                    'effective_price' => $salePrice, // dùng để sắp xếp
                    'discount_amount' => $discountAmount, // Số tiền đã giảm
                    'discount' => $discount ? [
                        'discount_percent' => $discount->discount_percent ?? 0,
                        'start_date' => $discount->start_date,
                        'end_date' => $discount->end_date,
                    ] : null,
                    'image' => $product->cover_image ? asset('uploads/' . $product->cover_image) : asset('placeholder.png'),
                ];
            });

            // Sắp xếp theo effective_price tăng dần
            $sorted = $productItems->sortBy('effective_price')->values();

            // Phân trang thủ công (do đã lấy & sắp xếp toàn bộ)
            $total = $sorted->count();
            $lastPage = (int) ceil($total / $perPage);
            $slice = $sorted->forPage($page, $perPage)->values()->all();

            return response()->json([
                'success' => true,
                'products' => $slice,
                'pagination' => [
                    'current_page' => $page,
                    'last_page' => $lastPage,
                    'per_page' => $perPage,
                    'total' => $total,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found.'
            ], 404);
        }
    }
    public function sortpricedescProduct($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $perPage = 12;
            $page = (int) request()->get('page', 1);

            // Lấy tất cả sản phẩm và eager-load discount còn hiệu lực
            $products = $supplier->products()
                ->with([
                    'discounts' => function ($query) {
                        $query->active();
                    }
                ])->get();

            // Transform products, tính effective_price = sale_price nếu có, ngược lại price
            $productItems = $products->map(function ($product) {
                $discount = $product->discounts->first(); // Lấy discount còn hiệu lực đầu tiên (nếu có)
                $originalPrice = (float) ($product->price ?? 0);
                $salePrice = (float) ($discount ? ($discount->sale_price ?? $originalPrice) : $originalPrice);
                $discountAmount = max(0, $originalPrice - $salePrice);

                return [
                    'supplier_name' => $product->supplier->name,
                    'product_id' => $product->product_id,
                    'name' => $product->product_name,
                    'price' => $originalPrice,
                    'sale_price' => $salePrice,
                    'effective_price' => $salePrice, // dùng để sắp xếp
                    'discount_amount' => $discountAmount, // Số tiền đã giảm
                    'discount' => $discount ? [
                        'discount_percent' => $discount->discount_percent ?? 0,
                        'start_date' => $discount->start_date,
                        'end_date' => $discount->end_date,
                    ] : null,
                    'image' => $product->cover_image ? asset('uploads/' . $product->cover_image) : asset('placeholder.png'),
                ];
            });

            // Sắp xếp theo effective_price giảm dần
            $sorted = $productItems->sortByDesc('effective_price')->values();

            // Phân trang thủ công (do đã lấy & sắp xếp toàn bộ)
            $total = $sorted->count();
            $lastPage = (int) ceil($total / $perPage);
            $slice = $sorted->forPage($page, $perPage)->values()->all();

            return response()->json([
                'success' => true,
                'products' => $slice,
                'pagination' => [
                    'current_page' => $page,
                    'last_page' => $lastPage,
                    'per_page' => $perPage,
                    'total' => $total,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found.'
            ], 404);
        }
    }
    public function sortnewestProduct($id)
    {
        try {
            $perPage = 12;

            $supplier = Supplier::findOrFail($id);

            // Lấy sản phẩm, eager-load discount còn hiệu lực, sắp xếp theo mới nhất
            $products = $supplier->products()
                ->with([
                    'discounts' => function ($query) {
                        $query->active();
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            // Transform products để thêm thông tin discount và giá sau giảm
            $productItems = $products->getCollection()->map(function ($product) {
                $discount = $product->discounts->first();
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
                    'created_at' => $product->created_at,
                ];
            })->values()->all();

            return response()->json([
                'success' => true,
                'products' => $productItems,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found.',
            ], 404);
        }
    }
    public function sortbestsellerProduct($id)
    {
        try {
            $perPage = 12;
            $page = (int) request()->get('page', 1);

            $supplier = Supplier::findOrFail($id);

            // Thử lấy count đơn hàng (orderItems) nếu relation tồn tại, nếu không fallback dùng sold_count hoặc 0
            try {
                $products = $supplier->products()
                    ->with([
                        'discounts' => function ($q) {
                            $q->active();
                        }
                    ])
                    ->withCount('orderItems as sales_count')
                    ->get();
            } catch (\Throwable $e) {
                $products = $supplier->products()
                    ->with([
                        'discounts' => function ($q) {
                            $q->active();
                        }
                    ])
                    ->get()
                    ->map(function ($p) {
                        $p->sales_count = (int) ($p->sold_count ?? 0);
                        return $p;
                    });
            }

            // Transform products để chuẩn hoá dữ liệu và tính giá sau giảm
            $productItems = $products->map(function ($product) {
                $discount = $product->discounts->first();
                $originalPrice = (float) ($product->price ?? 0);
                $salePrice = (float) ($discount ? ($discount->sale_price ?? $originalPrice) : $originalPrice);
                $discountAmount = max(0, $originalPrice - $salePrice);
                $salesCount = (int) ($product->sales_count ?? 0);

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
                    'sales_count' => $salesCount,
                ];
            });

            // Sắp xếp theo sales_count giảm dần, nếu bằng thì ưu tiên discount_amount giảm dần
            $sorted = $productItems
                ->sortByDesc('discount_amount')   // thứ yếu (để cùng sales_count, ưu tiên sản phẩm có giảm nhiều hơn)
                ->sortByDesc('sales_count')       // chính: bán nhiều trước
                ->values();

            // Phân trang thủ công
            $total = $sorted->count();
            $lastPage = (int) ceil($total / $perPage);
            $slice = $sorted->forPage($page, $perPage)->values()->all();

            return response()->json([
                'success' => true,
                'products' => $slice,
                'pagination' => [
                    'current_page' => $page,
                    'last_page' => $lastPage,
                    'per_page' => $perPage,
                    'total' => $total,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found.'
            ], 404);
        }
    }
}


