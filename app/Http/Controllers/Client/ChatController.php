<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // lấy danh sách người dùng đã chat với
        $chatUsers = Message::select('sender_id', 'receiver_id')
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->get()
            ->map(function ($message) use ($user) {
                return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
            })
            ->unique()
            ->values();

        $users = User::whereIn('id', $chatUsers)
            ->where('id', '!=', $user->id)
            ->get();

        return view('client.chat.index', compact('users'));
    }

    public function getMessages($userId)
    {
        $currentUser = Auth::user();
        
        // đánh dấu tin nhắn đã đọc
        Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // lấy tin nhắn giữa 2 người dùng
        $messages = Message::with(['sender', 'receiver'])
            ->where(function ($query) use ($currentUser, $userId) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $userId);
            })->orWhere(function ($query) use ($currentUser, $userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $currentUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'message_type' => 'text',
            'is_read' => false,
        ]);

        $message->load(['sender', 'receiver']);

        return response()->json($message);
    }

    public function getUnreadCount()
    {
        $unreadCount = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $unreadCount]);
    }

    public function getChatUsers()
    {
        $user = Auth::user();
        
        $chatUsers = Message::select('sender_id', 'receiver_id')
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->get()
            ->map(function ($message) use ($user) {
                return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
            })
            ->unique()
            ->values();

        $users = User::whereIn('id', $chatUsers)
            ->where('id', '!=', $user->id)
            ->get();

        // thêm số tin nhắn chưa đọc cho mỗi user
        foreach ($users as $userItem) {
            $userItem->unread_count = Message::where('sender_id', $userItem->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();
        }

        return response()->json($users);
    }
} 