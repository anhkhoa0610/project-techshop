<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function list()
    {
        $search = request('search');
    
        $page = intval(request('page', 1));
        $requestedPage = request('page');
    
        if ($page < 1 || ($requestedPage && intval($requestedPage) != $requestedPage)) {
            // Redirect to page 1 if invalid page parameter detected
            return redirect()->route('categories.list', ['search' => $search]);
        }

        $categories = Category::query()
            ->search($search)
            ->paginate(5, ['*'], 'page', $page);
        return view('crud-category.list', compact('categories'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
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
        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');

            $filename = $file->getClientOriginalName();

            $file->move(public_path('uploads'), $filename);

            if ($category->cover_image && file_exists(public_path('uploads/' . $category->cover_image))) {
                unlink(public_path('uploads/' . $category->cover_image));
            }
            $data['cover_image'] = $filename;
        }
        $category->fill($data);
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

        // Check for version conflict (optimistic locking)
        $requestedUpdatedAt = $request->input('updated_at');
        $currentUpdatedAt = $category->updated_at->format('Y-m-d H:i:s');
        
        if ($requestedUpdatedAt !== $currentUpdatedAt) {
            return response()->json([
                'success' => false,
                'conflict' => true,
                'message' => 'Dữ liệu đã bị thay đổi bởi người khác. Vui lòng tải lại trang và thử lại.',
                'current_updated_at' => $currentUpdatedAt,
            ], 409); // HTTP 409 Conflict
        }

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');

            $filename = $file->getClientOriginalName();

            // If there's an existing cover image and it's different from the new file name, remove the old file
            if ($category->cover_image && $category->cover_image !== $filename) {
                $oldPath = public_path('uploads/' . $category->cover_image);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // Move the new file into uploads
            $file->move(public_path('uploads'), $filename);

            $data['cover_image'] = $filename;
        }
        
        $category->update($data);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật danh mục thành công',
            'data' => $category,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $category_id)
    {
        $category = Category::findOrFail($category_id);
        // Delete uploaded cover image file if exists
        if ($category->cover_image) {
            $path = public_path('uploads/' . $category->cover_image);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa danh mục thành công!'
        ]);
    }
}
