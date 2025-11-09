<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CartItem;
use App\Models\Product;

class CartRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Cho phép tất cả (hoặc return auth()->check() nếu chỉ cho user đăng nhập)
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,user_id',
            'product_id' => 'required|integer|exists:products,product_id',
            'quantity' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Thiếu user_id',
            'user_id.integer' => 'Người dùng không tồn tại',
            'user_id.exists' => 'Người dùng không tồn tại',

            'product_id.required' => 'Thiếu product_id',
            'product_id.integer' => 'Sản phẩm không tồn tại',
            'product_id.exists' => 'Sản phẩm không tồn tại',

            'quantity.required' => 'Thiếu số lượng',
            'quantity.integer' => 'Số lượng phải là số nguyên',
            'quantity.min' => 'Số lượng tối thiểu là 1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $product = Product::find($this->product_id);

            if (!$product) {
                return; // Đã được rule exists xử lý rồi
            }
            
            $stock = $product->stock_quantity ?? 0;

            if($this->quantity >  $stock ){
                $validator->errors()->add('quantity', '
                Số lượng không hợp lệ (vượt quá số lượng kho).');
                return;
            }
            // Tính số lượng đã có trong giỏ hàng
            $currentQuantity = CartItem::where('user_id', $this->user_id)
                ->where('product_id', $this->product_id)
                ->sum('quantity');

            $newTotal = $currentQuantity + $this->quantity;

            if ($newTotal > $stock) {
                $validator->errors()->add('quantity', '
                Số lượng không hợp lệ(vượt quá số lượng kho).
                Bạn đã có (' . $currentQuantity . ') sản phẩm trong giỏ hàng.
                Không thể thêm số lượng đã chọn vào giỏ hàng 
                . ');
            }
        });
    }


}
