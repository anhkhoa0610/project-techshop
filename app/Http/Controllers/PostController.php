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
        $posts = Post::latest()->paginate(10); 

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post) 
    {
        return view('posts.show', [
            'post' => $post,
        ]);
    }

    public function loadPostsApi(Request $request)
    {
        $posts = Post::latest()->paginate(5); 
        return response()->json([
            'success' => true,
            'data' => $posts->items(),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'total' => $posts->total(),
            'per_page' => $posts->perPage(),
        ]);
    }
}