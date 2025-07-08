<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;
use App\Models\FeatureItem;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'items' => 'required|array',
            'items.*.icon' => 'required',
            'items.*.title' => 'required',
            'items.*.description' => 'required',
        ]);

        $data = $request->only('title', 'subtitle');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('features', 'public');
        }

        $feature = Feature::create($data);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'items' => 'required|array',
            'items.*.icon' => 'required',
            'items.*.title' => 'required',
            'items.*.description' => 'required',
        ]);

        $data = $request->only('title', 'subtitle');

        if ($request->hasFile('image')) {
            if ($feature->image && Storage::disk('public')->exists($feature->image)) {
                Storage::disk('public')->delete($feature->image);
            }
            $data['image'] = $request->file('image')->store('features', 'public');
        }

        $feature->update($data);

        $feature->items()->delete();
        foreach ($request->items as $item) {
            $feature->items()->create($item);
        }

        return redirect()->route('admin.features.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);

        if ($feature->image && Storage::disk('public')->exists($feature->image)) {
            Storage::disk('public')->delete($feature->image);
        }

        $feature->delete();

        return redirect()->route('admin.features.index')->with('success', 'Xoá thành công!');
    }
}
