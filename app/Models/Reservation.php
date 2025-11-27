<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'cart_id',
        'quantity',
        'temporary_order_id',
        'expires_at',
    ];

    protected $dates = ['expires_at'];

    public static function createForCartItems($userId, $cartItems, $temporaryOrderId, $minutes = 15)
    {
        $expires = Carbon::now()->addMinutes($minutes);
        $created = [];
        foreach ($cartItems as $item) {
            $created[] = self::create([
                'user_id' => $userId,
                'product_id' => $item->product_id,
                'cart_id' => $item->cart_id ?? null,
                'quantity' => $item->quantity,
                'temporary_order_id' => $temporaryOrderId,
                'expires_at' => $expires,
            ]);
        }
        return $created;
    }

    public static function releaseByTemporaryOrderId($temporaryOrderId)
    {
        return self::where('temporary_order_id', $temporaryOrderId)->delete();
    }

    public static function releaseExpired()
    {
        return self::where('expires_at', '<=', Carbon::now())->delete();
    }
}
