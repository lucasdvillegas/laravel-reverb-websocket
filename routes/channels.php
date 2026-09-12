<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);
    
    if (!$conversation) {
        return false;
    }

    return (int) $conversation->user_one_id === (int) $user->id || 
           (int) $conversation->user_two_id === (int) $user->id;
});

Broadcast::channel('online', function ($user) {
    return ['id' => $user->id];
});