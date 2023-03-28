<?php

namespace App\Exports;

use App\Models\Chat;
use Maatwebsite\Excel\Concerns\FromCollection;

class ChatExport implements FromCollection
{
    protected $chat_id;

    public function __construct($chat_id)
    {
        $this->chat_id = $chat_id;
    }

    public function collection()
    {
        $chat = Chat::select('context')->where("id", $this->chat_id)->first();
        $chatContext =  collect($chat->context);

        return $chatContext;
    }
}
