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
}
