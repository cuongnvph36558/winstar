<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TinTuc;
use Illuminate\Http\Request;

class TinTucController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tinTucs = TinTuc::all();
        return view('admin.tin_tuc.index', compact('tinTucs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tin_tuc.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate
        $data = $request->validate([
            'tieu_de' => 'required|string|max:255',
            'hinh_anh' => 'nullable|image',
            'noi_dung' => 'required|string',
            'trang_thai' => 'required|boolean',
        ]);

        // Upload hình ảnh nếu có
        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('tin_tuc', 'public');
        }

        TinTuc::create($data);

        return redirect()->route('admin.tin-tuc.index')->with('success', 'Thêm tin tức thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TinTuc $tinTuc)
    {
        return view('admin.tin_tuc.edit', compact('tinTuc'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TinTuc $tinTuc)
    {
        $data = $request->validate([
            'tieu_de' => 'required',
            'noi_dung' => 'required',
            'hinh_anh' => 'nullable|image',
            'trang_thai' => 'required|boolean'
        ]);

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('tin_tuc', 'public');
        }

        $tinTuc->update($data);
        return redirect()->route('admin.tin-tuc.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TinTuc $tinTuc)
    {
        $tinTuc->delete();
        return redirect()->route('admin.tin-tuc.index');
    }
    public function toggle($id)
    {
        $tinTuc = TinTuc::findOrFail($id);
        $tinTuc->trang_thai = !$tinTuc->trang_thai;
        $tinTuc->save();

        return redirect()->back()->with('success', 'Đã đổi trạng thái thành công.');
    }
}
