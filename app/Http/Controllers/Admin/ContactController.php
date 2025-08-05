<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::with('user')->where('status', 'pending');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $contacts = $query->latest()->paginate(15);
        
        return view('admin.contact.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::with('user')->findOrFail($id);
        
        // Mark as read if it's pending
        if ($contact->status === 'pending') {
            $contact->update(['status' => 'pending']); // Keep as pending but mark as viewed
        }
        
        return view('admin.contact.detail', compact('contact'));
    }

    public function edit($id)
    {
        $contact = Contact::with('user')->findOrFail($id);
        return view('admin.contact.edit', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|min:10',
        ], [
            'reply.required' => 'Vui lòng nhập nội dung phản hồi.',
            'reply.min' => 'Nội dung phản hồi phải có ít nhất 10 ký tự.',
        ]);

        $contact = Contact::with('user')->findOrFail($id);
        
        $contact->update([
            'reply' => $request->reply,
            'status' => 'resolved',
        ]);

        // Send notification to user if they have an email
        if ($contact->user && $contact->user->email) {
            $this->sendReplyNotification($contact);
        }
        
        return redirect()->route('contacts.index')->with('success', 'Phản hồi đã được gửi thành công.');
    }

    public function replied(Request $request)
    {
        $query = Contact::with('user')->where('status', 'resolved');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('reply', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $contacts = $query->latest()->paginate(15);

        return view('admin.contact.trash', compact('contacts'));
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        
        return redirect()->route('contacts.replied')->with('success', 'Liên hệ đã được xóa thành công.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,mark_resolved',
            'contacts' => 'required|array',
            'contacts.*' => 'exists:contacts,id'
        ]);

        $contacts = Contact::whereIn('id', $request->contacts);

        switch ($request->action) {
            case 'delete':
                $contacts->delete();
                $message = 'Các liên hệ đã được xóa thành công.';
                break;
            case 'mark_resolved':
                $contacts->update(['status' => 'resolved']);
                $message = 'Các liên hệ đã được đánh dấu là đã phản hồi.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    private function sendReplyNotification($contact)
    {
        try {
            $user = $contact->user;
            $subject = 'Phản hồi cho tin nhắn: ' . $contact->subject;
            
            $data = [
                'user_name' => $user->name,
                'contact_subject' => $contact->subject,
                'contact_message' => $contact->message,
                'admin_reply' => $contact->reply,
                'reply_date' => $contact->updated_at->format('d/m/Y H:i'),
                'contact_url' => route('client.contact.index')
            ];

            Mail::send('emails.contact-reply', $data, function($message) use ($user, $subject) {
                $message->to($user->email, $user->name)
                        ->subject($subject);
            });

        } catch (\Exception $e) {
            // Log error but don't break the flow
            Log::error('Failed to send contact reply notification: ' . $e->getMessage());
        }
    }
}
