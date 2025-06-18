<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index()
    {
        $storages = Storage::orderBy('id', 'desc')->paginate(10);
        return view('admin.storage.index', compact('storages'));
    }

    public function create()
    {
        return view('admin.storage.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'capacity' => 'required|string|max:255|unique:storages,capacity',
        ], [
            'capacity.required' => 'Dung lượng không được để trống',
            'capacity.string' => 'Dung lượng phải là chuỗi',
            'capacity.max' => 'Dung lượng tối đa 255 ký tự',
            'capacity.unique' => 'Đã có trong bảng storage, vui lòng nhập giá trị khác đi',
        ]);
        Storage::create($request->only('capacity'));

        return redirect()->route('admin.storage.index')->with('success', 'Storage added successfully!');
    }

    public function edit($id)
    {
        $storage = Storage::findOrFail($id);
        return view('admin.storage.edit', compact('storage'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'capacity' => 'required|string|max:255|unique:storages,capacity',
        ], [
            'capacity.required' => 'Dung lượng không được để trống',
            'capacity.string' => 'Dung lượng phải là chuỗi',
            'capacity.max' => 'Dung lượng tối đa 255 ký tự',
            'capacity.unique' => 'Đã có trong bảng storage, vui lòng nhập giá trị khác đi',
        ]);

        Storage::findOrFail($id)->update($request->only('capacity'));

        return redirect()->route('admin.storage.index')->with('success', 'Storage updated successfully!');
    }

    public function destroy($id)
    {
        Storage::findOrFail($id)->delete();

        return redirect()->route('admin.storage.index')->with('success', 'Storage deleted successfully!');
    }
}
