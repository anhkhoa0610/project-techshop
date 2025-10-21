<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Voucher;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



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
    ];

    // Nếu có cột datetime tự động
    protected $dates = ['order_date', 'created_at', 'updated_at'];



    // Mỗi đơn hàng thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Mỗi đơn hàng có thể liên kết với 1 voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucher_id');
    }

    // Mỗi đơn hàng có nhiều chi tiết đơn hàng
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }

    // Hàm cập nhật giá, áp dụng mã giảm mỗi khi thay đổi,tạo mới chi tiết đơn hàng
    public function updateTotalPrice()
    {
        // 1. Tính tổng giá gốc
        $total = $this->orderDetails()->sum(DB::raw('quantity * unit_price'));
        $finalPrice = $total;

        // 2. Áp dụng mã giảm giá (nếu có)
        if ($this->voucher_id) {
            $voucher = Voucher::where('voucher_id', $this->voucher_id)
                ->where('status', 'active')
                ->first();

            if ($voucher) {
                $today = now()->toDateString();

                if ($voucher->start_date <= $today && $voucher->end_date >= $today) {
                    if ($voucher->discount_type === 'percent') {
                        $discountAmount = $total * ($voucher->discount_value / 100);
                    } else {
                        $discountAmount = $voucher->discount_value;
                    }

                    $finalPrice = max($total - $discountAmount, 0);
                }
            }
        }


        // 3. Giảm thêm 20% cho sinh viên TDC
        if ($this->user && $this->user->is_tdc_student) {
            $finalPrice *= 0.8; // giảm 20%
        }

        // 4. Cập nhật lại order
        $this->total_price = $finalPrice;
        $this->save();
    }

    public function getOrderDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y H:i');
    }
}
