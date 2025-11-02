<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'review_id';

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'review_date',
    ];

    protected $casts = [
        'review_date' => 'datetime',
        'rating' => 'integer',
    ];

    // Validation rules
    public static $rules = [
        'product_id' => 'required|exists:products,product_id',
        'user_id' => 'required|exists:users,user_id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
        'review_date' => 'required|date|before_or_equal:today',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Boot method để thêm các event
    // protected static function boot()
    // {
    //     parent::boot();

    //     // Trước khi tạo review mới
    //     static::creating(function ($review) {
    //         // Kiểm tra user đã review sản phẩm này chưa
    //         $exists = self::where('product_id', $review->product_id)
    //             ->where('user_id', $review->user_id)
    //             ->exists();

    //         if ($exists) {
    //             throw new \Exception('Người dùng này đã đánh giá sản phẩm này rồi.');
    //         }
    //     });
    // }
}
