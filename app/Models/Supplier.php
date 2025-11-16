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

}
