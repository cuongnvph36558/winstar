<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'content',
        'image',
        'status',
        'published_at',
    ];
    
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
    public function comments()
{
    return $this->hasMany(Comment::class, 'post_id');
}

}
