<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;

class ChatGptStoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreChatRequest $request, string $id = null)
    {
        $messages = [];

        if ($id) {
            $chat = Chat::findOrFail($id);
            $messages = $chat->context;
        }

        $messages[] = ['role' => 'user', 'content' => $request->input('promt')];

        if ($request->system != "") {
            $system= ['role' => 'system', 'content' => $request->input('system')];
            array_unshift($messages,$system);
        }

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'temperature' => $request->temperature,
            'frequency_penalty' => $request->frequency_penalty,
            'presence_penalty' => $request->presence_penalty,
            'max_tokens' => $request->max_tokens,
            'top_p' => $request->top_p,

        ]);

        $messages[] = ['role' => 'assistant', 'content' => $response->choices[0]->message->content];
        $chat = Chat::updateOrCreate(
            [
                'id' => $id,
                'user_id' => Auth::id()
            ],
            [
                'context' => $messages
            ]
        );

        return redirect()->route('chat.show', [$chat->id]);
    }
}
