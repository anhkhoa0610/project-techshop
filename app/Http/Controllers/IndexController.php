<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $topProducts = Product::orderByDesc('volume_sold')->limit(4)->get();
        $newProducts = Product::orderByDesc('release_date')->limit(4)->get();
        $allProducts = Product::all();
        return view('index', compact('topProducts', 'newProducts', 'allProducts'));
    }

    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->limit(8)->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

}
