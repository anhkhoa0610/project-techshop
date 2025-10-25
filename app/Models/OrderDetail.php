<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'order_details';

    // Khóa chính
    protected $primaryKey = 'order_detail_id';

    // Cho phép Eloquent tự động tăng ID
    public $incrementing = true;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'int';

    // Các cột cho phép gán hàng loạt (mass assignment)
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];

    /**
     * Mỗi chi tiết đơn hàng thuộc về một đơn hàng
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * Mỗi chi tiết đơn hàng thuộc về một sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // tìm kiếm sản phẩm theo tên
    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->whereRelation('product', 'product_name', 'like', '%' . $search . '%')
            ->orWhere('order_detail_id', 'like', '%' . $search . '%');
        }

        return $query;
    }

    public function scopeOfOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

}
