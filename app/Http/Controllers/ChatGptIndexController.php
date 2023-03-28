<?php

namespace App\Http\Controllers;

use App\Exports\ChatExport;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class ChatGptIndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $id = null): Response
    {
        return Inertia::render('Chat/ChatIndex', [
            'chat' => fn () => $id ? Chat::findOrFail($id) : null,
            'messages' => Chat::latest()->where('user_id', Auth::id())->get()
        ]);
    }

    public function exportChat($chat_id)
    {
        return Excel::download(new ChatExport($chat_id), 'chatExport.xlsx');
    }

}
