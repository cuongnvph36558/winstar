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
        $tinTucs = TinTuc::all();
        return view('admin.tin_tuc.create', compact('tinTucs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        TinTuc::create($data);
        return redirect()->route('admin.tin-tuc.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TinTuc $tinTuc)
    {
        return view('admin.tin_tuc.show', compact('tinTuc'));
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
}
