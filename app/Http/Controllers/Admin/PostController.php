<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // public function index()
    // {
    //     $posts = Post::with('author')->latest()->paginate(10);
    //     return view('admin.posts.index', compact('posts'));
    // }

    // public function create()
    // {
    //     return view('admin.posts.create');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'content' => 'required|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     $imagePath = $request->hasFile('image') ? $request->file('image')->store('posts', 'public') : null;

    //     Post::create([
    //         'author_id' => Auth::id(),
    //         'title' => $request->title,
    //         'content' => $request->content,
    //         'image' => $imagePath,
    //         'status' => $request->status ?? 1,
    //         'published_at' => now(),
    //     ]);

    //     return redirect()->route('admin.posts.index')->with('success', 'Tạo bài viết thành công');
    // }

    // public function edit(Post $post)
    // {
    //     return view('admin.posts.edit', compact('post'));
    // }

    // public function update(Request $request, Post $post)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'content' => 'required|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'published_at' => 'nullable|date',
    //     ]);

    //     if ($request->hasFile('image')) {
    //         $post->image = $request->file('image')->store('posts', 'public');
    //     }

    //     $post->update([
    //         'title' => $request->title,
    //         'content' => $request->content,
    //         'status' => $request->status ?? $post->status,
    //         'published_at' => $request->published_at,
    //         'image' => $post->image,
    //     ]);

    //     return redirect()->route('admin.posts.edit', $post->id)->with('success', 'Cập nhật bài viết thành công');
    // }

    // public function destroy(Post $post)
    // {
    //     $post->delete();
    //     return redirect()->route('admin.posts.index')->with('success', 'Đã xóa bài viết');
    // }

    // public function show(Post $post)
    // {
    //     return view('admin.posts.detail', compact('post'));
    // }
}