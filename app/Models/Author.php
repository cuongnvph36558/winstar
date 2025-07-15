<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'bio',
        'avatar',
        'website',
    ];

    /**
     * Get the posts for the author.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
