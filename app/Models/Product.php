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

    public function scopeFilterByCategory($query, $category_id = null)
    {
        return $query->when($category_id && $category_id != null, function ($q) use ($category_id) {
            $q->where('category_id', $category_id);
        });
    }

    public function scopeFilterBySupplier($query, $supplier_id = null)
    {
        return $query->when($supplier_id && $supplier_id != null, function ($q) use ($supplier_id) {
            $q->where('supplier_id', $supplier_id);
        });
    }

    public function scopeInStock($query, $status = 0)
    {
        return $query->when($status != 0, function ($q) use ($status) {
            if ($status == 1) {
                // Còn hàng
                $q->where('stock_quantity', '>', 0);
            } elseif ($status == 2) {
                // Hết hàng
                $q->where('stock_quantity', '<=', 0);
            }
        });
    }

    public function scopeReleasedWithin($query, $days = null)
    {
        return $query->when($days !== null, function ($q) use ($days) {
            $q->where('release_date', '>=', now()->subDays($days));
        });
    }

    public function scopeRating($query, $rating = null, $order = 'desc')
    {
        if (!is_null($rating)) {
            $min = $rating - 0.5;
            $max = $rating + 0.4;

            // chỉ thêm having, không withAvg ở đây
            $query->havingRaw('reviews_avg_rating BETWEEN ? AND ?', [$min, $max]);
        }

        return $query->orderBy('reviews_avg_rating', $order);
    }


    public function scopeFilter($query, $min_price = null, $max_price = null, $category_id = 0, $supplier_id = 0, $rating = null, $in_stock = null, $days = null)
    {
        return $query
            ->priceRange($min_price, $max_price)
            ->filterByCategory($category_id)
            ->filterBySupplier($supplier_id)
            ->rating($rating)
            ->inStock($in_stock)
            ->releasedWithin($days);
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
        $query = $this->reviews()->with('user')->latest('review_date');

        if ($rating) {
            $query->where('rating', $rating);
        }

        return $query->paginate(5)->appends(['rating' => $rating]);
    }

    // Hàm lấy thông tin review
    public function getReviews()
    {
        $query = $this->reviews()->with('user')->latest();
        return $query->paginate(5);
    }

    public function discounts()
    {
        return $this->hasMany(ProductDiscount::class, 'product_id', 'product_id');
    }

}
