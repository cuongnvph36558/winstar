<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function GetAllCategory(Request $request)
    {
        try {
            $query = Category::query();

            if($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if($request->filled('parent_id')) {
                $query->where('parent_id', $request->parent_id);
            }

            $categories = $query->orderBy('id', 'desc')->paginate(10);
            
            return view('admin.category.index-category', compact('categories'));

        } catch (\Exception $e) {
            Log::error('Error in GetAllCategory: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while fetching categories');
        }
    }

    public function CreateCategory()
    {
        $categories = Category::where('parent_id', 0)->get();
        return view('admin.category.create-category', compact('categories'));
    }

    public function StoreCategory(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3|max:255|unique:categories,name',
                'parent_id' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:1000'
            ], [
                'name.required' => 'Tên danh mục không được để trống',
                'name.string' => 'Tên danh mục phải là chuỗi ký tự',
                'name.min' => 'Tên danh mục phải có ít nhất 3 ký tự',
                'name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
                'name.unique' => 'Tên danh mục này đã tồn tại',
                'parent_id.required' => 'Vui lòng chọn loại danh mục',
                'parent_id.numeric' => 'Loại danh mục không hợp lệ',
                'parent_id.min' => 'Loại danh mục không hợp lệ',
                'description.string' => 'Mô tả phải là chuỗi ký tự',
                'description.max' => 'Mô tả không được vượt quá 1000 ký tự'
            ]);

            $category = new Category();
            $category->name = $validated['name'];
            $category->parent_id = $validated['parent_id'];
            $category->description = $validated['description'] ?? null;
            $category->save();

            return redirect()->route('admin.category.index-category')
                ->with('success', 'Danh mục đã được tạo thành công');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)
                        ->withInput();
        } catch (\Exception $e) {
            Log::error('Error in StoreCategory: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi tạo danh mục')
                        ->withInput();
        }
    }

    public function ShowCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.detail-category', compact('category'));
    }

    public function EditCategory($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('parent_id', 0)->get();
        return view('admin.category.edit-category', compact('category', 'categories'));
    }

    public function UpdateCategory(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            // Check if this is a parent category with children
            if ($category->parent_id == 0) {
                $hasChildren = Category::where('parent_id', $id)->exists();
                if ($hasChildren) {
                    return back()->with('error', 'Không thể sửa danh mục cha đang có danh mục con')
                                ->withInput();
                }
            }

            $validated = $request->validate([
                'name' => 'required|string|min:3|max:255|unique:categories,name,' . $id,
                'parent_id' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:1000'
            ], [
                'name.required' => 'Tên danh mục không được để trống',
                'name.string' => 'Tên danh mục phải là chuỗi ký tự',
                'name.min' => 'Tên danh mục phải có ít nhất 3 ký tự',
                'name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
                'name.unique' => 'Tên danh mục này đã tồn tại',
                'parent_id.required' => 'Vui lòng chọn loại danh mục',
                'parent_id.numeric' => 'Loại danh mục không hợp lệ',
                'parent_id.min' => 'Loại danh mục không hợp lệ',
                'description.string' => 'Mô tả phải là chuỗi ký tự',
                'description.max' => 'Mô tả không được vượt quá 1000 ký tự'
            ]);

            $category->name = $validated['name'];
            $category->parent_id = $validated['parent_id'];
            $category->description = $validated['description'] ?? null;
            $category->save();

            return redirect()->route('admin.category.index-category')
                ->with('success', 'Danh mục đã được cập nhật thành công');

        } catch (\Exception $e) {
            Log::error('Error in UpdateCategory: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật danh mục')
                        ->withInput();
        }
    }

    public function DeleteCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
            
            // Check if parent category has child categories
            if ($category->parent_id == 0) {
                $childCategories = Category::where('parent_id', $category->id)->exists();
                if ($childCategories) {
                    return back()->with('error', 'Cannot delete this parent category because it has child categories');
                }
            }

            $category->delete();
            
            return redirect()->route('admin.category.index-category')
                ->with('success', 'Category has been deleted successfully');
                
        } catch (\Exception $e) {
            Log::error('Error in DeleteCategory: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the category');
        }
    }

    public function TrashCategory()
    {
        try {
            $categories = Category::onlyTrashed()
                ->orderBy('deleted_at', 'desc')
                ->get();

            return view('admin.category.restore-category', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error in TrashCategory: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while retrieving trashed categories');
        }
    }

    public function RestoreCategory($id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);
            
            // Check if this is a child category and its parent is still deleted
            if ($category->parent_id != 0) {
                $parent = Category::withTrashed()->find($category->parent_id);
                if ($parent && $parent->trashed()) {
                    return back()->with('error', 'Cannot restore child category while parent category is still deleted');
                }
            }
            
            $category->restore();
            
            return redirect()->route('admin.category.restore-category')
                ->with('success', 'Category has been restored successfully');
        } catch (\Exception $e) {
            Log::error('Error in RestoreCategory: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while restoring the category');
        }
    }
    public function ForceDeleteCategory($id)
    {
        try {
            $category = Category::onlyTrashed()->findOrFail($id);
            
            // Check if category has child categories
            $childCategories = Category::onlyTrashed()
                ->where('parent_id', $id)
                ->exists();
                
            if ($childCategories) {
                return back()->with('error', 'Cannot delete category permanently while it has child categories');
            }
            
            $category->forceDelete();
            
            return redirect()->route('admin.category.restore-category')
                ->with('success', 'Category has been deleted permanently');
        } catch (\Exception $e) {
            Log::error('Error in ForceDeleteCategory: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the category permanently');
        }
    }
}
