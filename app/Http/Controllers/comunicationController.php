<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; 

class comunicationController extends Controller
{
    /**
     * Hiển thị giao diện AI Assistant.
     *
     * @return \Illuminate\View\View
     */
    public function showAssistant()
    {
        return view('page.comunication');
    }

    /**
     * Proxy request to the Node.js AI server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(Request $request)
    {
        // Lấy dữ liệu từ frontend
        $userMessage = $request->input('message');
        $topic = $request->input('topic');
        $history = $request->input('history');

        // Ghi log dữ liệu nhận được để gỡ lỗi
        Log::info('Received request from frontend:', [
            'message' => $userMessage,
            'topic' => $topic,
            'history' => $history
        ]);

        try {
            // Gửi yêu cầu POST đến Node.js AI server
            $response = Http::post('http://localhost:3000/chat', [
                'message' => $userMessage,
                'topic' => $topic,
                'history' => $history,
            ]);

            // Kiểm tra xem yêu cầu có thành công không
            if ($response->successful()) {
                $aiResponse = $response->json();
                Log::info('Received response from Node.js AI server:', $aiResponse);
                return response()->json($aiResponse);
            } else {
                // Ghi log lỗi nếu Node.js server trả về lỗi
                Log::error('Error response from Node.js AI server:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json([
                    'error' => 'Failed to get response from AI proxy.',
                    'details' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            // Ghi log lỗi nếu có ngoại lệ xảy ra (ví dụ: Node.js server không chạy)
            Log::error('Exception when connecting to Node.js AI server:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'error' => 'Could not connect to AI service. Please ensure the Node.js server is running.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}