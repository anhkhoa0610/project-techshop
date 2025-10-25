<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Voucher extends Model
{
  use HasFactory;

  protected $primaryKey = 'voucher_id';
  public $incrementing = true;
  protected $keyType = 'int';

  protected $fillable = [
    'code',
    'discount_type',
    'discount_value',
    'start_date',
    'end_date',
    'status',
  ];

  public static function search($search)
  {
    return self::where('code', 'like', '%' . $search . '%');
  }
}
