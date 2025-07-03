<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'content'    => 'required|string|max:1000',
        ]);

        Comment::create([
            'product_id' => $request->product_id,
            'user_id'    => auth()->id(),
            'content'    => $request->content,
            'status'     => 1, // hoặc 0 nếu muốn phải duyệt mới hiển thị
        ]);

return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi!')->withFragment('commen');


    }
}
