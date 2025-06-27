<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'guest_name',
        'content',
        'status',
    ];

    // Bình luận thuộc về sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Bình luận thuộc về user (nếu có đăng nhập)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
