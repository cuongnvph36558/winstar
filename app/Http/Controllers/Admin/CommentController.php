<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $products = Product::withCount([
            'comments as active_comments_count' => fn ($q) => $q->where('status', 1),
            'comments as hidden_comments_count' => fn ($q) => $q->where('status', 0),
            'comments as total_comments_count'
        ])
        ->withMax('comments', 'created_at') // Lấy thời gian bình luận mới nhất
        ->has('comments')
        ->orderByDesc('comments_max_created_at') // Sắp xếp theo thời gian bình luận mới nhất
        ->get();

        return view('admin.comment.index', compact('products'));
    }
    public function showCommentsByProduct($id)
    {
        $product = Product::with('comments')->findOrFail($id);
        return view('admin.comment.by-product', compact('product'));
    }

    public function toggleStatus($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->status = $comment->status == 1 ? 0 : 1;
        $comment->save();

        return back()->with('success', 'Cập nhật trạng thái bình luận thành công.');
    }



}
