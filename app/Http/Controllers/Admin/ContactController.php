<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
{
     $contacts = Contact::with('user')
        ->where('status', 'pending') // chỉ lấy liên hệ có status = 0 (pending)
        ->latest()
        ->paginate(10);
    return view('admin.contact.index', compact('contacts'));
}
    public function show($id)
{
    $contact = Contact::with('user')->findOrFail($id);
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
        'reply' => 'required|string'
    ]);

    $contact = Contact::with('user')->findOrFail($id);
     $contact->update([
            'reply' => $request->reply,
            'status' => 'resolved',
        ]);
    return redirect()->route('contacts.index', $contact->id)->with('success', 'Phản hồi đã được gửi thành công.');
}

public function replied()
{
    $contacts = Contact::with('user')
        ->where('status', 'resolved') // hoặc có thể là 1 nếu bạn dùng kiểu boolean
        ->latest()
        ->paginate(10);

    return view('admin.contact.trash', compact('contacts'));
}

public function destroy($id)
{
       // Tìm bản ghi trong bảng contact
        $contact = Contact::find($id);
    
        if ($contact) {
            $contact->delete();  // Xóa bản ghi trực tiếp khỏi cơ sở dữ liệu
            return redirect()->route('contacts.replied')->with('success', 'Liên hệ đã bị xóa.');
        }
    
        return redirect()->route('contacts.replied')->with('error', 'Liên hệ không tồn tại.');
}

}
