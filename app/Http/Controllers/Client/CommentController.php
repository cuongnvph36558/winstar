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
        try {
            event(new CommentAdded($comment, auth()->user()));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast CommentAdded event: ' . $e->getMessage());
        }
        
        // Dispatch UserActivity event for admin notification
        try {
            event(new UserActivity(auth()->user(), 'add_comment', [
                'product_id' => $comment->product_id,
                'comment_id' => $comment->id
            ]));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast UserActivity event: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi!')->withFragment('comment');
    }
}
