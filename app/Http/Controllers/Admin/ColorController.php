<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $query = Color::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $colors = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.color.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.color.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Color::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.color.index')->with('success', 'Thêm thành công!');
    }

    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return view('admin.color.edit', compact('color'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $color = Color::findOrFail($id);
        $color->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.color.index')->with('success', 'Sửa thành công!');
    }

    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();

        return redirect()->route('admin.color.index')->with('success', 'Xóa thành công!');
    }
}
