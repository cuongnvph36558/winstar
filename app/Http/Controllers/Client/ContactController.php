<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    
    public function index()
    {
        $userContacts = collect();
        
        if (Auth::check()) {
            // Lấy tất cả contact của user hiện tại
            $userContacts = Contact::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('client.contact.index', compact('userContacts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ], [
            'subject.required' => 'Vui lòng nhập tiêu đề tin nhắn.',
            'subject.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.min' => 'Nội dung tin nhắn phải có ít nhất 10 ký tự.',
        ]);

        Contact::create([
            'user_id' => Auth::id(), // null nếu không đăng nhập
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Tin nhắn của bạn đã được gửi thành công! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }

    public function checkNewReplies()
    {
        if (!Auth::check()) {
            return response()->json(['has_new_replies' => false]);
        }

        // Kiểm tra xem có liên hệ nào mới được phản hồi không
        $newReplies = Contact::where('user_id', Auth::id())
            ->where('status', 'resolved')
            ->where('updated_at', '>', now()->subMinutes(5)) // Phản hồi trong 5 phút gần đây
            ->count();

        return response()->json([
            'has_new_replies' => $newReplies > 0,
            'count' => $newReplies
        ]);
    }


}
