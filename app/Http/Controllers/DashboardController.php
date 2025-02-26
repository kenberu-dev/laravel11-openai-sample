<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class DashboardController extends Controller
{
    public function index()
    {
        return inertia('Dashboard', ['message' => '']);
    }

    public function send()
    {
        $rescentMessage = Message::where('user_id', Auth::user()->id)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get()
                            ->map(fn ($message) => "Q: {$message->question}\nA: {$message->response}\n")
                            ->implode("\n");
        Log::info($rescentMessage);
        $prompt = "利用者の情報\n"
                . "希望職種: 事務\n"
                . "障害・特性: うつ病\n"
                . "これまでの相談履歴:" .$rescentMessage . "\n\n"
                . "今回の質問: ". request('prompt');
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'developer',
                    'content' => 'あなたは就労移行支援の専門家であり、支援員に対して具体的なアドバイスを提供する役割を担っています。あなたは、利用者の状況や特性や過去の相談履歴を理解した上で、支援員が実際に実践できる、具体的かつ段階的な支援方法を提案してください。回答は、支援計画の立案や、支援員が利用者をサポートする際の具体的な行動指針となるように心がけてください。また、回答は必ず以下の点を含めてください。\n
                    1. どのような目的や目標を持ってそのサポートを行うべきか。\n
                    2. どのような手順、ステップでそのサポートを行うべきか。\n
                    3. どのような点に注意しながらサポートを行うべきか。\n
                    4. 就労移行支援員が具体的な支援内容をイメージできる内容にしてください。',                ],
                ['role' => 'user', 'content' => $prompt],
            ]
        ]);

        $message = $result->choices[0]->message->content;

        Message::create([
            'user_id' => Auth::user()->id,
            'question' => request('prompt'),
            'response' => $message,
        ]);

        return inertia('Dashboard', ['message' => $message]);
    }
}
