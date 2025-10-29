<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class UIProductDetailsController extends Controller
{
    public function show($id)
    {
        $product = Product::find($id);
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Danh sách danh mục',
        //     'data' => $product->images
        // ], 200);
     
        return view('ui-product-details.product', compact('product'));
    }
}
