<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'description',
        'stock_quantity',
        'price',
        'cover_image',
        'volume_sold',
        'category_id',
        'supplier_id',
        'warranty_period',
        'release_date',
        'embed_url_review',
    ];

    // Mỗi sản phẩm thuộc về 1 danh mục
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // Mỗi sản phẩm thuộc về 1 nhà cung cấp
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    // Mỗi sản phẩm có nhiều chi tiết đơn hàng
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id', 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function scopePriceRange($query, $min_price = null, $max_price = null)
    {
        return $query
            ->when($min_price !== null && $max_price !== null, function ($q) use ($min_price, $max_price) {
                $q->whereBetween('price', [$min_price, $max_price]);
            })
            ->when($min_price !== null && $max_price === null, function ($q) use ($min_price) {
                $q->where('price', '>=', $min_price);
            })
            ->when($max_price !== null && $min_price === null, function ($q) use ($max_price) {
                $q->where('price', '<=', $max_price);
            });
    }

    public function scopeFilterByCategory($query, $category_id = 0)
    {
        return $query->when($category_id && $category_id != 0, function ($q) use ($category_id) {
            $q->where('category_id', $category_id);
        });
    }

    public function scopeFilterBySupplier($query, $supplier_id = 0)
    {
        return $query->when($supplier_id && $supplier_id != 0, function ($q) use ($supplier_id) {
            $q->where('supplier_id', $supplier_id);
        });
    }

    public function scopeFilter($query, $min_price = null, $max_price = null, $category_id = 0, $supplier_id = 0)
    {
        return $query
            ->priceRange($min_price, $max_price)
            ->filterByCategory($category_id)
            ->filterBySupplier($supplier_id);
    }

    public function scopeSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('product_name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }
        return $query;
    }

    public function scopeWithVideo($query)
    {
        return $query->whereNotNull('embed_url_review');
    }
    // Mỗi sản phẩm có nhiều đánh giá (reviews)
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    // Hàm tổng hợp số lượng đánh giá theo từng sao
    public function getReviewSummary()
    {
        $counts = $this->reviews()
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();


        $counts['all'] = array_sum($counts);

        return $counts;
    }

    // Hàm lấy bình luận lọc theo số sao
    public function getFilteredReviews($rating = null)
    {
        $query = $this->reviews()->with('user')->latest();

        if ($rating) {
            $query->where('rating', $rating);
        }

        return $query->paginate(5);
    }

    // Hàm lấy thông tin review
    public function getReviews()
    {
        $query = $this->reviews()->with('user')->latest();
        return $query->paginate(5);
    }











}
