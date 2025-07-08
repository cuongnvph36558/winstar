<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;
use App\Models\FeatureItem;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::with('items')->get();
        return view('admin.features.index', compact('features'));
    }

    public function create()
    {
        return view('admin.features.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'subtitle' => 'nullable',
            'image' => 'nullable|string',
            'items' => 'required|array',
            'items.*.icon' => 'required',
            'items.*.title' => 'required',
            'items.*.description' => 'required',
        ]);

        $feature = Feature::create($request->only('title', 'subtitle', 'image'));

        foreach ($request->items as $item) {
            $feature->items()->create($item);
        }

        return redirect()->route('admin.features.index')->with('success', 'Tạo thành công!');
    }

    public function edit($id)
    {
        $feature = Feature::with('items')->findOrFail($id);
        return view('admin.features.edit', compact('feature'));
    }

    public function update(Request $request, $id)
    {
        $feature = Feature::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'subtitle' => 'nullable',
            'image' => 'nullable|string',
            'items' => 'required|array',
            'items.*.icon' => 'required',
            'items.*.title' => 'required',
            'items.*.description' => 'required',
        ]);

        $feature->update($request->only('title', 'subtitle', 'image'));

        // Xoá tất cả rồi tạo lại (đơn giản nhất)
        $feature->items()->delete();
        foreach ($request->items as $item) {
            $feature->items()->create($item);
        }

        return redirect()->route('admin.features.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);
        $feature->delete();
        return redirect()->route('admin.features.index')->with('success', 'Xoá thành công!');
    }
}
