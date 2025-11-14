<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $query = trim($request->input('message', ''));
        $openrouterApiKey = config('services.openrouter.api_key');
        $baseUrl = config('services.openrouter.base_url');
        $model = config('services.openrouter.model');

        if (empty($query)) {
            return response()->json(['reply' => 'Vui lòng nhập câu hỏi.']);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $openrouterApiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => 'http://localhost',
            'X-Title' => 'Chatbot',
        ])->post($baseUrl, [
                    'model' => $model,
                    'max_tokens' => 300, // 👈 Giới hạn độ dài câu trả lời
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Bạn là một trợ lý AI trả lời bằng tiếng Việt, ngắn gọn, lịch sự, nếu trả lời sản phẩm thì không cần liệt kê nhiều thông số"
                        ],
                        [
                            'role' => 'user',
                            'content' => $query
                        ]
                    ]
                ]);

        $data = $response->json();

        $reply = $data['choices'][0]['message']['content'] ?? 'Xin lỗi, tôi chưa có câu trả lời cho câu hỏi này.';

        // Dọn dẹp các token lỗi
        $reply = preg_replace('/<\｜.*?\｜>/', '', $reply);
        $reply = preg_replace('/<\|.*?\|>/', '', $reply);
        $reply = str_replace(['▁', '�'], ' ', $reply);
        $reply = preg_replace('/\s+/', ' ', $reply);
        $reply = trim($reply);


        return response()->json(['reply' => $reply]);
    }
}
