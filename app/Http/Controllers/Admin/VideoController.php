<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();
        return view('admin.video.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.video.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'subtitle' => 'nullable',
            'video' => 'required|mimes:mp4,webm,mov|max:51200',
            'background' => 'nullable|image|mimes:jpg,jpeg,png,webp'
        ]);

        if ($request->hasFile('video')) {
            $data['path'] = $request->file('video')->store('videos', 'public');
        }

        if ($request->hasFile('background')) {
            $data['background'] = $request->file('background')->store('videos/backgrounds', 'public');
        }

        Video::create($data);
        return redirect()->route('admin.video.index')->with('success', 'Thêm video thành công.');
    }

    public function edit(Video $video)
    {
        return view('admin.video.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $data = $request->validate([
            'title' => 'required',
            'subtitle' => 'nullable',
            'path' => 'nullable|mimes:mp4,webm,mov|max:51200',
            'background' => 'nullable|image|mimes:jpg,jpeg,png,webp'
        ]);

        if ($request->hasFile('path')) {
            if ($video->path) {
                Storage::disk('public')->delete($video->path);
            }
            $data['path'] = $request->file('path')->store('videos', 'public');
        }

        if ($request->hasFile('background')) {
            if ($video->background) {
                Storage::disk('public')->delete($video->background);
            }
            $data['background'] = $request->file('background')->store('videos/backgrounds', 'public');
        }

        $video->update($data);

        return redirect()->route('admin.video.index')->with('success', 'Cập nhật video thành công.');
    }

    public function destroy(Video $video)
    {
        if ($video->path) {
            Storage::disk('public')->delete($video->path);
        }
        if ($video->background) {
            Storage::disk('public')->delete($video->background);
        }
        $video->delete();
        return redirect()->route('admin.video.index')->with('success', 'Xoá video thành công.');
    }
}
