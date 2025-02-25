<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class DashboardController extends Controller
{
    public function index()
    {
        return inertia('Dashboard');
    }

    public function send()
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => 'あなたは就労移行支援の専門家です。利用者の状況に合わせた具体的で簡潔なアドバイスを提供してください。'],
                ['role' => 'user', 'content' => request('prompt')],
            ]
        ]);

        $message = $result->choices[0]->message->content;

        return inertia('Dashboard', ['message' => $message]);
    }
}
