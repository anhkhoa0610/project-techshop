<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Order extends Model
{
    use HasFactory;

    // Tên bảng trong DB
    protected $table = 'orders';

    // Khóa chính
    protected $primaryKey = 'order_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Các cột cho phép gán giá trị hàng loạt
    protected $fillable = [
        'user_id',
        'order_date',
        'status',
        'shipping_address',
        'payment_method',
        'voucher_id',
        'total_price',
    ];

    // Nếu có cột datetime tự động
    protected $dates = ['order_date', 'created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | QUAN HỆ (Relationships)
    |--------------------------------------------------------------------------
    */

    // Mỗi đơn hàng thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Mỗi đơn hàng có thể liên kết với 1 voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucher_id');
    }

    // Nếu bạn có bảng order_items (chi tiết sản phẩm trong đơn)
    // public function items()
    // {
    //     return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    // }
}
