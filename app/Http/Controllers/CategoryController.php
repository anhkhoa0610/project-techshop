<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function list()
    {
        // Khởi tạo query gốc
        $query = Category::query();

        // Nếu có tham số tìm kiếm
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where('category_name', 'like', '%' . $search . '%');
        }

        // Phân trang sau khi lọc
        $categories = $query->paginate(5);

        // Gửi dữ liệu sang view
        return view('crud-category.list', compact('categories'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Khởi tạo query gốc
        $query = Category::query();

        // Nếu có tham số tìm kiếm
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where('category_name', 'like', '%' . $search . '%');
        }

        // Phân trang sau khi lọc
        $categories = $query->paginate(5);

        // Gửi dữ liệu sang view
        return response()->json([
            'success' => true,
            'message' => 'Danh sách danh mục',
            'data' => $categories
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = new Category();
        $category->fill($request->all());
        $category->save();
        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Thành công!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::where('category_id', $id)->first();

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy danh mục với ID ' . $id,
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Chi tiết danh mục',
            'data' => $category,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Cập nhật danh mục thất bại',
            ]);
        }
        $category->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật danh mục thành công',
            'data' => $category,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $category_id)
    {
        $category = Category::findOrFail($category_id);
        $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa danh mục thành công!'
        ]);
    }
}
