<?php

namespace App\Models;

// BẮT BUỘC PHẢI CÓ DÒNG NÀY
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spec extends Model
{
    // BẮT BUỘC PHẢI CÓ DÒNG NÀY
    use HasFactory; 

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
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}