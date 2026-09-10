<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSend;

class MessageController extends Controller
{
    public function show(User $user)
    {
        $authId = auth()->id();
        $targetId = $user->id;

        // Buscar si ya existe la sala en cualquier dirección
        $conversation = Conversation::where(function ($query) use ($authId, $targetId) {
            $query->where('user_one_id', $authId)->where('user_two_id', $targetId);
        })->orWhere(function ($query) use ($authId, $targetId) {
            $query->where('user_one_id', $targetId)->where('user_two_id', $authId);
        })->first();

        // Si no existe, se crea la sala nueva
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $authId,
                'user_two_id' => $targetId,
            ]);
        }

        // Cargar los mensajes de esta conversación específica
        $messages = $conversation->messages()->with('user:id,name')->orderBy('created_at', 'asc')->get();

        return Inertia::render('Message', [
            'conversation' => $conversation,
            'messages' => $messages,
            'chatUser' => $user, // Datos del usuario con el que se está chateando
        ]);
    }

    public function store(Request $request): void
    {
        $validated = $request->validate([
            'content' => 'string|required',
            'conversation_id' => 'required|exists:conversations,id'
        ]);

        $message = Message::create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'conversation_id' => $validated['conversation_id'],
        ]);

        $message->load('user:id,name');
        
        MessageSend::dispatch($message);
    }
}
