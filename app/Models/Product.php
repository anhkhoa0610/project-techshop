<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'description',
        'stock_quantity',
        'price',
        'cover_image',
        'volume_sold',
        'category_id',
        'supplier_id',
        'warranty_period',
        'release_date',
    ];

    // Mỗi sản phẩm thuộc về 1 danh mục
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // Mỗi sản phẩm thuộc về 1 nhà cung cấp
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}
