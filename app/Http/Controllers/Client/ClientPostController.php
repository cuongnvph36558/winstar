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

        $popularPosts = Post::where('status', 1)
            ->whereNotNull('image')
            ->orderByDesc('view')
            ->limit(5)
            ->get();

        return view('client.blog.list-blog', compact('posts', 'popularPosts'));
    }

public function show($id)
{
    $post = Post::with('author')->where('status', 1)->findOrFail($id);

    $popularPosts = Post::where('status', 1)
        ->whereNotNull('image')
        ->orderByDesc('view')
        ->limit(5)
        ->get();

    return view('client.blog.single-blog', compact('post', 'popularPosts'));
}

}
