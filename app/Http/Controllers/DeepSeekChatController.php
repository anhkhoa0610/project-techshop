<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeepSeekChatController extends Controller
{
    public function chat(Request $request)
    {
        $query = trim($request->input('message', ''));

        if (empty($query)) {
            return response()->json(['reply' => 'Vui lòng nhập câu hỏi.']);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type' => 'application/json',
            'HTTP-Referer' => 'http://localhost',
            'X-Title' => 'Laravel DeepSeek Chatbot',
        ])->post(env('OPENROUTER_BASE_URL') . '/chat/completions', [
                    'model' => env('OPENROUTER_MODEL'),
                    'max_tokens' => 300, // 👈 Giới hạn độ dài câu trả lời
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Bạn là một trợ lý AI trả lời bằng tiếng Việt, ngắn gọn, tự nhiên.
                                        Trả lời bằng văn bản thuần tiếng Việt, không chứa ký tự kỹ thuật."
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
