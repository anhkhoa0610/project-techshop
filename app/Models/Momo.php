<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Momo extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'request_id',
        'trans_id',
        'amount',
        'order_info',
        'result_code',
        'message',
        'pay_url',
        'status',
    ];
    public function order()
{
    return $this->belongsTo(Order::class, 'order_id', 'id');
}
}
