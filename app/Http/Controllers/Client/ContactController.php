<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

    public function test()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated']);
        }

        $userId = Auth::id();
        $allContacts = Contact::all();
        $userContacts = Contact::where('user_id', $userId)->get();

        return response()->json([
            'user_id' => $userId,
            'all_contacts_count' => $allContacts->count(),
            'user_contacts_count' => $userContacts->count(),
            'all_contacts' => $allContacts->map(function($contact) {
                return [
                    'id' => $contact->id,
                    'user_id' => $contact->user_id,
                    'subject' => $contact->subject,
                    'status' => $contact->status,
                    'has_reply' => !empty($contact->reply),
                    'reply' => $contact->reply,
                    'created_at' => $contact->created_at,
                    'updated_at' => $contact->updated_at
                ];
            }),
            'user_contacts' => $userContacts->map(function($contact) {
                return [
                    'id' => $contact->id,
                    'user_id' => $contact->user_id,
                    'subject' => $contact->subject,
                    'status' => $contact->status,
                    'has_reply' => !empty($contact->reply),
                    'reply' => $contact->reply,
                    'created_at' => $contact->created_at,
                    'updated_at' => $contact->updated_at
                ];
            })
        ]);
    }

    public function createTestContact()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated']);
        }

        $contact = Contact::create([
            'user_id' => Auth::id(),
            'subject' => 'Test Contact - ' . now()->format('Y-m-d H:i:s'),
            'message' => 'Đây là tin nhắn test để kiểm tra chức năng phản hồi. Vui lòng phản hồi tin nhắn này.',
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'contact_id' => $contact->id,
            'message' => 'Đã tạo contact test thành công!'
        ]);
    }
}
