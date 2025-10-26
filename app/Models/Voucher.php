<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Voucher extends Model
{
  use HasFactory;

  protected $table = 'vouchers';
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
    if (empty($term)) {
      return $query;
    }
    return self::where('code', 'like', '%' . $search . '%');
  }

  public static function createVoucher(array $validated)
  {
    return self::create($validated);
  }

  public static function updateVoucher($id, array $validated)
  {
    $voucher = self::findOrFail($id);
    $voucher->update($validated);
    return $voucher;
  }

  public static function deleteVoucher($id)
  {
    $voucher = self::findOrFail($id);
    $voucher->delete();
  }
}
