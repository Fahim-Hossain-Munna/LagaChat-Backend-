<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $chats = Chat::where(function ($query) use ($request) {
            $query->where('sender_id', $request->sender_id)
                ->where('receiver_id', $request->receiver_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('sender_id', $request->receiver_id)
                ->where('receiver_id', $request->sender_id);
        })->orderBy('created_at', 'asc')->get();

        return $this->json('success', ['chats' => $chats]);
    }
    public function store(Request $request)
    {
        Chat::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'sent_at' => now(),
            'seen' => true,
            'seen_at' => now(),
            'created_at' => now()
        ]);

        return $this->json('success', ['message' => 'Message sent successfully']);
    }
}
