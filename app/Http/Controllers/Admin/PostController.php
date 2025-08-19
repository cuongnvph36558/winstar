<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('author');
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $posts = $query->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('posts', 'public') : null;

        // Tìm hoặc tạo author từ user hiện tại
        $author = \App\Models\Author::firstOrCreate(
            ['email' => Auth::user()->email],
            [
                'name' => Auth::user()->name,
                'bio' => 'Admin author',
                'avatar' => null,
                'website' => null,
            ]
        );

        Post::create([
            'author_id' => $author->id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'status' => $request->status ?? 'draft',
            'published_at' => $request->status == 'published' ? now() : null,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Tạo bài viết thành công');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $updateData = [
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status ?? $post->getAttribute('status'),
            'published_at' => $request->published_at,
        ];

        // Nếu status là published và chưa có published_at, set published_at = now()
        if ($request->status == 'published' && !$request->published_at) {
            $updateData['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $updateData['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($updateData);

        return redirect()->route('admin.posts.edit', $post->id)->with('success', 'Cập nhật bài viết thành công');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Đã xóa bài viết');
    }

    public function show(Post $post)
    {
        return view('admin.posts.detail', compact('post'));
    }
    
}