<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutPage;

class AboutController extends Controller
{
    public function index()
    {
        $about = AboutPage::first();
        return view('admin.about.index', compact('about'));
    }

    public function create()
    {
        return view('admin.about.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $about = new AboutPage();
        $about->title = $request->title;
        $about->content = $request->content;
        $about->save();

        return redirect()->route('admin.about.index')->with('success', 'Thêm nội dung thành công.');
    }

    public function edit()
    {
        $about = AboutPage::first();
        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $about = AboutPage::first();
        if (!$about) {
            $about = new AboutPage();
        }

        $about->title = $request->title;
        $about->content = $request->content;
        $about->save();

        return redirect()->route('admin.about.index')->with('success', 'Cập nhật thành công.');
    }
}
