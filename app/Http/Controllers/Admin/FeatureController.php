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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'items' => 'required|array|min:1',
            'items.*.icon' => 'required|string|max:100',
            'items.*.title' => 'required',
            'items.*.description' => 'required',
        ]);

        $data = $request->only('title', 'subtitle');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('features', 'public');
        }

        $feature = Feature::create($data);

        foreach ($request->items as $index => $item) {
            $itemData = [
                'title' => $item['title'],
                'description' => $item['description'],
                'icon' => $item['icon']
            ];

            $feature->items()->create($itemData);
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
        $feature = Feature::with('items')->findOrFail($id);

        $request->validate([
            'title' => 'required',
            'subtitle' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'items' => 'required|array|min:1',
            'items.*.icon' => 'required|string|max:100',
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

        $oldItems = $feature->items;
        $feature->items()->delete();

        foreach ($request->items as $index => $item) {
            $itemData = [
                'title' => $item['title'],
                'description' => $item['description'],
                'icon' => $item['icon'],
            ];

            $feature->items()->create($itemData);
        }

        return redirect()->route('admin.features.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);

        if ($feature->image && Storage::disk('public')->exists($feature->image)) {
            Storage::disk('public')->delete($feature->image);
        }

        foreach ($feature->items as $item) {
            if ($item->icon && Storage::disk('public')->exists($item->icon)) {
                Storage::disk('public')->delete($item->icon);
            }
        }

        $feature->items()->delete();
        $feature->delete();

        return redirect()->route('admin.features.index')->with('success', 'Xoá thành công!');
    }
}
