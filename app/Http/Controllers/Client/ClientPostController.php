<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class ClientPostController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')
            ->where('status', 1)
            ->orderByDesc('published_at')
            ->paginate(6);

        return view('client.blog.list-blog', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::with('author')->where('status', 1)->findOrFail($id);

        return view('client.blog.single-blog', compact('post'));
    }
}
