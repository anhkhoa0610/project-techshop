<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'description',
    ];

    // Một danh mục có thể có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }

    // Tìm kiếm danh mục theo tên hoặc mã danh mục
    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('category_name', 'like', '%' . $search . '%')
                    ->orWhere('category_id', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }
}
