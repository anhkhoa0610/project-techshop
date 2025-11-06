<?php

// app/Http/Controllers/PostController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // <-- Thêm Model Post

class PostController extends Controller
{
    /**
     * Hiển thị danh sách các bài viết.
     */
    public function index()
    {
        // Lấy tất cả bài viết, sắp xếp mới nhất lên đầu, và phân trang
        $posts = Post::latest()->paginate(10); // Ví dụ: 10 bài mỗi trang

        // Trả về view 'posts.index' và truyền biến $posts vào đó
        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Hiển thị chi tiết một bài viết.
     */
    public function show(Post $post) // Dùng Route Model Binding
    {
        // Laravel sẽ tự động tìm Post dựa trên ID trên URL
        return view('posts.show', [
            'post' => $post,
        ]);
    }
}