<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Message;
use App\Events\MessageSend;

class MessageController extends Controller
{
    public function index(): Response
    {   
        $messages = Message::query()
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get();

        return Inertia::render('Message', [
            'messages' => $messages,
        ]);
    }

    public function store(Request $request): void
    {
        $validated = $request->validate([
            'content' => 'string|required',
            'receiver_id' => 'required|exists:users,id'
        ]);

        $message = Message::create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
        ]);

        $message->save();
        $message->load(['user:id,name', 'receiver:id,name']);
        
        MessageSend::dispatch($message);
    }
}
