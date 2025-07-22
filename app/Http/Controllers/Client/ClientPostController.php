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
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->paginate(6);

        $popularPosts = Post::where('status', 'published')
            ->whereNotNull('image')
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();



        return view('client.blog.list-blog', compact('posts', 'popularPosts'));
    }

public function show($id)
{
    $post = Post::with('author')->where('status', 'published')->findOrFail($id);

    $popularPosts = Post::where('status', 'published')
        ->whereNotNull('image')
        ->orderByDesc('published_at')
        ->limit(5)
        ->get();

    return view('client.blog.single-blog', compact('post', 'popularPosts'));
}

}
