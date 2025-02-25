<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class DashboardController extends Controller
{
    public function index()
    {
        return inertia('Dashboard', ['message' => '']);
    }

    public function send()
    {
        $prompt = "利用者の情報\n"
                . "希望職種: 事務\n"
                . "障害・特性: ADHD\n"
                . "質問: ". request('prompt');
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'developer', 'content' => 'あなたは障害福祉の専門家です。利用者の状況に合わせた具体的なアドバイスを提供してください。その際、支援員の支援可能内容に限定して回答してください。'],
                ['role' => 'user', 'content' => $prompt],
            ]
        ]);

        $message = $result->choices[0]->message->content;

        return inertia('Dashboard', ['message' => $message]);
    }
}
