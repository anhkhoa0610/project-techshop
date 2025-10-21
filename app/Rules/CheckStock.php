<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Product;

class CheckStock implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productId = request('product_id'); // Lấy product_id từ request
        $product = Product::find($productId);

        if (!$product) {
            $fail('Sản phẩm không tồn tại.');
            return;
        }

        if ($value > $product->stock_quantity) {
            $fail('Số lượng vượt quá số lượng tồn kho (' . $product->stock_quantity . ').');
        }
    }
}
