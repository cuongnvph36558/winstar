<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class AdminVideoController extends Controller
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
        $request->validate([
            'title' => 'required',
            'subtitle' => 'nullable',
            'video' => 'required|file|mimes:mp4,webm,mov|max:51200',
            'background' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $videoPath = null;
        $backgroundPath = null;

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');
        }

        if ($request->hasFile('background')) {
            $backgroundPath = $request->file('background')->store('videos/backgrounds', 'public');
        }

        Video::create([
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
            'video_path' => $videoPath,
            'background' => $backgroundPath,
        ]);

        return redirect()->route('admin.video.index')->with('success', 'Thêm video thành công.');
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('admin.video.edit', compact('video'));
    }

    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'subtitle' => 'nullable',
            'video' => 'nullable|file|mimes:mp4,webm,mov|max:51200',
            'background' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $data = [
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
        ];

        if ($request->hasFile('video')) {
            if ($video->video_path) {
                Storage::disk('public')->delete($video->video_path);
            }
            $data['video_path'] = $request->file('video')->store('videos', 'public');
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

    public function destroy($id)
    {
        $video = Video::findOrFail($id);

        if ($video->video_path) {
            Storage::disk('public')->delete($video->video_path);
        }
        if ($video->background) {
            Storage::disk('public')->delete($video->background);
        }

        $video->delete();
        return redirect()->route('admin.video.index')->with('success', 'Xoá video thành công.');
    }
}
