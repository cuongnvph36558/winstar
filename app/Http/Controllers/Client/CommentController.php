<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Events\CommentAdded;
use App\Events\UserActivity;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'content'    => 'required|string|max:1000',
            'rating'     => 'nullable|integer|min:1|max:5',
        ]);

        $comment = Comment::create([
            'product_id' => $request->product_id,
            'user_id'    => auth()->id(),
            'content'    => $request->content,
            'rating'     => $request->rating ?? 5,
            'status'     => 1, // hoặc 0 nếu muốn phải duyệt mới hiển thị
        ]);

        // Dispatch realtime event
        event(new CommentAdded($comment, auth()->user()));
        
        // Dispatch UserActivity event for admin notification
        event(new UserActivity(auth()->user(), 'add_comment', [
            'product_id' => $comment->product_id,
            'comment_id' => $comment->id
        ]));

        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi!')->withFragment('comment');
    }
}
