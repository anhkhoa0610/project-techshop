<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sale_price',
        'original_price',
        'discount_percent',
        'start_date',
        'end_date',
    ];

    /**
     * Quan hệ với bảng products
     * product_id ở bảng này → product_id ở bảng products
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Scope lọc các khuyến mãi còn hiệu lực
     */
    public function scopeActive($query)
    {
        $now = now();

        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
        });
    }
}
