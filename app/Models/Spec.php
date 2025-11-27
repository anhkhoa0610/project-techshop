<?php

namespace App\Models;

// BẮT BUỘC PHẢI CÓ DÒNG NÀY
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;

class Spec extends Model
{
    // BẮT BUỘC PHẢI CÓ DÒNG NÀY
    use HasFactory;
    protected $table = 'specs';
    protected $primaryKey = 'spec_id';
    /**
     * Các cột được phép gán hàng loạt.
     */
    protected $fillable = [
        'product_id',
        'name',
        'value'
    ];

    /**
     * Lấy sản phẩm mà thông số này thuộc về.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public static function createSpec(array $validated)
    {
        return self::create($validated);
    }

    public static function updateSpec($id, array $validated)
    {
        $spec = self::findOrFail($id);
        $spec->update($validated);
        return $spec;
    }

    public static function deleteSpec($id)
    {
        $spec = self::findOrFail($id);
        $spec->delete();
    }

    public static function paginate($perPage)
    {
        return self::query()->paginate($perPage);
    }

    public static function search($search)
    {
        return self::where('product_id', 'like', '%' . $search . '%');
    }

    public function scopeForDropdown()
    {
        return Product::select('product_id', 'product_name');
    }
}