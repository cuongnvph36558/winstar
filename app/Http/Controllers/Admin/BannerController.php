<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('created_at')->get();
        return view('admin.banner.index-banner', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.create-banner');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'required|image',
            'link' => 'nullable|string|max:255', 
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:1,0',
        ],[
            'required' => 'Trường :attribute là bắt buộc',
            'image' => 'Trường :attribute phải là ảnh',
            'string' => 'Trường :attribute phải là chuỗi',
            'max' => 'Trường :attribute không được vượt quá :max ký tự',
            'date' => 'Trường :attribute phải là ngày',
            'after_or_equal' => 'Trường :attribute phải sau hoặc bằng ngày bắt đầu',
            'in' => 'Trường :attribute không hợp lệ',
        ],[
            'title' => 'Tiêu đề',
            'image_url' => 'Hình ảnh',
            'link' => 'Liên kết',
            'start_date' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc', 
            'status' => 'Trạng thái',
        ]);

        $path = null;
        if($request->hasFile('image_url')){
            $path = $request->file('image_url')->store('banners', 'public');
        }

        Banner::create([
            'title' => $request->title,
            'link' => $request->link,
            'image_url' => $path,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.banner.index-banner')
            ->with('success', 'Banner đã được tạo thành công!');
    }

    public function detail($id)
    {
        $banner = Banner::find($id);
        return view('admin.banner.detail-banner', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $banner = Banner::find($id);
        return view('admin.banner.edit-banner', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'nullable|image',
            'link' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:1,0',
        ],[
            'required' => 'Trường :attribute là bắt buộc',
            'image' => 'Trường :attribute phải là ảnh',
            'string' => 'Trường :attribute phải là chuỗi', 
            'max' => 'Trường :attribute không được vượt quá :max ký tự',
            'date' => 'Trường :attribute phải là ngày',
            'after_or_equal' => 'Trường :attribute phải sau hoặc bằng ngày bắt đầu',
            'in' => 'Trường :attribute không hợp lệ',
        ],[
            'title' => 'Tiêu đề',
            'image_url' => 'Hình ảnh',
            'link' => 'Liên kết', 
            'start_date' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc',
            'status' => 'Trạng thái',
        ]);

        $data = [
            'title' => $request->title,
            'link' => $request->link,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status
        ];

        if ($request->hasFile('image_url')) {
            // Delete old image if exists
            if ($banner->image_url) {
                Storage::disk('public')->delete($banner->image_url);
            }
            $data['image_url'] = $request->file('image_url')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banner.index-banner')
            ->with('success', 'Banner đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
        */
        public function destroy($id)
        {
            $banner = Banner::findOrFail($id);
            
            // Soft delete the banner
            $banner->delete();
            
            return redirect()->route('admin.banner.index-banner')
                ->with('success', 'Banner đã được xóa thành công!');
        }

    public function trash()
    {
        $banners = Banner::onlyTrashed()->get();
        return view('admin.banner.restore-banner', compact('banners'));
    }

    public function restore($id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);
        $banner->restore();
        return redirect()->route('admin.banner.index-banner')
            ->with('success', 'Banner đã được khôi phục thành công!');
    }
    public function forceDelete($id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);
        $banner->forceDelete();
        return redirect()->route('admin.banner.index-banner')
            ->with('success', 'Banner đã được xóa vĩnh viễn!');
    }
}
