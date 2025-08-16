<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatBotController extends Controller
{
    public function index()
    {
        $stats = [
            'total_messages' => ChatMessage::count(),
            'total_users' => ChatMessage::distinct('user_id')->count(),
            'today_messages' => ChatMessage::whereDate('created_at', today())->count(),
            'unread_messages' => ChatMessage::where('sender', 'bot')->where('is_read', false)->count(),
        ];

        $recentMessages = ChatMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $topUsers = ChatMessage::select('user_id', DB::raw('count(*) as message_count'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('message_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.chatbot.index', compact('stats', 'recentMessages', 'topUsers'));
    }

    public function conversations()
    {
        $conversations = ChatMessage::select('user_id', DB::raw('MAX(created_at) as last_message'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('last_message', 'desc')
            ->paginate(20);

        return view('admin.chatbot.conversations', compact('conversations'));
    }

    public function showConversation($userId)
    {
        $user = User::findOrFail($userId);
        $messages = ChatMessage::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chatbot.conversation', compact('user', 'messages'));
    }

    public function analytics()
    {
        // Messages per day for the last 30 days
        $dailyMessages = ChatMessage::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Messages by hour
        $hourlyMessages = ChatMessage::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // User vs Bot messages
        $messageTypes = ChatMessage::select('sender', DB::raw('COUNT(*) as count'))
            ->groupBy('sender')
            ->get();

        return view('admin.chatbot.analytics', compact('dailyMessages', 'hourlyMessages', 'messageTypes'));
    }

    public function settings()
    {
        return view('admin.chatbot.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'auto_response' => 'boolean',
            'welcome_message' => 'string|max:500',
            'offline_message' => 'string|max:500',
        ]);

        // Update settings logic here
        // You can store these in config or database

        return redirect()->back()->with('success', 'Cài đặt chatbot đã được cập nhật');
    }

    public function exportData(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30));
        $endDate = $request->input('end_date', now());

        $messages = ChatMessage::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get();

        $filename = 'chatbot_data_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($messages) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'User', 'Message', 'Sender', 'Read', 'Created At']);
            
            foreach ($messages as $message) {
                fputcsv($file, [
                    $message->id,
                    $message->user ? $message->user->name : 'Unknown',
                    $message->message,
                    $message->sender,
                    $message->is_read ? 'Yes' : 'No',
                    $message->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function deleteConversation($userId)
    {
        ChatMessage::where('user_id', $userId)->delete();
        
        return redirect()->route('admin.chatbot.conversations')
            ->with('success', 'Cuộc hội thoại đã được xóa');
    }

    public function markAllAsRead()
    {
        ChatMessage::where('sender', 'bot')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Tất cả tin nhắn đã được đánh dấu là đã đọc');
    }
} 