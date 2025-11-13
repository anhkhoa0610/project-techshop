<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'description',
        'cover_image',
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

    public function scopeWithTotalRevenue($query)
    {
        // $query ở đây là một truy vấn Category
        return $query->join('products', 'categories.category_id', '=', 'products.category_id')
            ->join('order_details', 'products.product_id', '=', 'order_details.product_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->where('orders.status', 'completed')
            ->groupBy('categories.category_id', 'categories.category_name')
            ->select([ 
                'categories.category_name as category_name',
                DB::raw('SUM(order_details.quantity * order_details.unit_price) as total_revenue')
            ]);
    }
}
