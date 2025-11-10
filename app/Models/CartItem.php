<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
  use HasFactory;
  protected $table = 'cart_items';
  protected $primaryKey = 'cart_id';
  protected $fillable = ['user_id', 'product_id', 'quantity'];

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id', 'product_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'user_id');
  }

  /**
   *  Xử lý thêm hoặc cập nhật sản phẩm vào giỏ hàng.
   */
  public static function addOrUpdate($userId, $productId, $quantity = 1)
  {
    $cartItem = self::where('user_id', $userId)
      ->where('product_id', $productId)
      ->first();

    if ($cartItem) {
      // Đã có -> cộng dồn số lượng
      $cartItem->increment('quantity', $quantity);
    } else {
      // Chưa có -> tạo mới
      $cartItem = self::create([
        'user_id' => $userId,
        'product_id' => $productId,
        'quantity' => $quantity,
      ]);
    }

    // Load lại thông tin product + specs
    $cartItem->load('product.specs');

    return $cartItem;
  }

}
