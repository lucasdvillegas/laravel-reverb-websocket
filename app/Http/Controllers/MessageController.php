<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Message;

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
        $message = $request->validate([
            'content' => 'string|required'
        ]);

        Message::create([
            'content' => $message['content'],
            'user_id' => auth()->id()
        ]);
    }
}
