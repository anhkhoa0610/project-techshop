<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\OrderDetail;
use App\Models\Product;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'description',
        'logo',
    ];

    // Một nhà cung cấp có thể cung cấp nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id', 'supplier_id');
    }

    public static function searchByName($search)
    {
        return self::where('name', 'like', '%' . $search . '%');
    }

    public function handleLogoUpload($file)
    {
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);
        return $filename;
    }

    public function deleteOldLogo()
    {
        if (
            $this->logo && $this->logo !== 'placeholder.png'
            && file_exists(public_path('uploads/' . $this->logo))
        ) {
            unlink(public_path('uploads/' . $this->logo));
        }
    }

    public static function createSupplier(array $validated)
    {
        if (isset($validated['logo'])) {
            $validated['logo'] = (new self)->handleLogoUpload($validated['logo']);
        } else {
            $validated['logo'] = 'placeholder.png';
        }

        return self::create($validated);
    }

    public static function updateSupplier($id, array $validated)
    {
        $supplier = self::findOrFail($id);

        if (isset($validated['logo'])) {
            $supplier->deleteOldLogo();
            $validated['logo'] = $supplier->handleLogoUpload($validated['logo']);
        }

        $supplier->update($validated);
        return $supplier;
    }

    public static function deleteSupplier($id)
    {
        $supplier = self::findOrFail($id);

        $supplier->delete();
    }

    public static function paginate($perPage)
    {
        return self::query()->paginate($perPage);
    }

    /**
     * Tự động thêm accessor này vào JSON.
     */
    protected $appends = ['total_products_sold'];

    // ... (các hàm khác) ...

    // Hàm accessor của bạn
    public function getTotalProductsSoldAttribute()
    {
        // ... (code đã sửa như ở trên) ...
        return OrderDetail::whereHas('product', function ($query) {
            $query->where('supplier_id', $this->supplier_id);
        })->sum('quantity');
    }

    public function orderDetails()
    {
        return $this->hasManyThrough(
            OrderDetail::class,
            Product::class,
            'supplier_id', // Khóa ngoại trên bảng products
            'product_id',  // Khóa ngoại trên bảng order_details
            'supplier_id', // Khóa chính trên bảng suppliers
            'product_id'   // Khóa chính trên bảng products
        );
    }

    public function getProductsForApi($sortType = 'default', $perPage = 6)
    {
        // 1. Khởi tạo query với eager loading discounts và supplier
        $query = $this->products()
            ->with([
                'discounts' => function ($q) {
                    $q->active();
                },
                'supplier' // Vẫn load supplier để đảm bảo cấu trúc JSON không đổi
            ]);

        // 2. Xử lý Sắp Xếp
        $now = now()->toDateTimeString();

        switch ($sortType) {
            case 'newest':
                $query->orderBy('products.created_at', 'desc');
                break;

            case 'price_asc':
            case 'price_desc':
                $direction = ($sortType == 'price_asc') ? 'asc' : 'desc';
                $rawSql = "COALESCE(
                    (SELECT sale_price FROM product_discounts 
                        WHERE product_discounts.product_id = products.product_id 
                        AND (product_discounts.start_date IS NULL OR product_discounts.start_date <= ?)
                        AND (product_discounts.end_date IS NULL OR product_discounts.end_date >= ?)
                        ORDER BY sale_price ASC 
                        LIMIT 1), 
                    products.price
                ) $direction";
                $query->orderByRaw($rawSql, [$now, $now]);
                break;

            case 'best_seller':
                $query->withCount('orderDetails as sales_count')
                    ->orderBy('sales_count', 'desc');
                break;

            case 'best_discount':
                $rawSql = "COALESCE(
                    (SELECT discount_percent FROM product_discounts 
                        WHERE product_discounts.product_id = products.product_id 
                        AND (product_discounts.start_date IS NULL OR product_discounts.start_date <= ?)
                        AND (product_discounts.end_date IS NULL OR product_discounts.end_date >= ?)
                        ORDER BY discount_percent DESC
                        LIMIT 1), 
                    0
                ) DESC";
                $query->orderByRaw($rawSql, [$now, $now]);
                break;

            default:
                $query->orderBy('products.created_at', 'desc');
                break;
        }

        // 3. Trả về kết quả phân trang
        return $query->paginate($perPage);
    }

}
