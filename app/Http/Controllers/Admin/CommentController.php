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
        ->has('comments') //chỉ lấy sản phẩm có ít nhất 1 comment
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
