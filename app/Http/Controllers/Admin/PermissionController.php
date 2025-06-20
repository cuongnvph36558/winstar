<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->paginate(15);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string|max:500'
        ], [
            'name.required' => 'Tên quyền là bắt buộc',
            'name.unique' => 'Tên quyền đã tồn tại',
            'name.max' => 'Tên quyền không được quá 255 ký tự',
            'description.max' => 'Mô tả không được quá 500 ký tự'
        ]);

        try {
            Permission::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            return redirect()->route('admin.permissions.index')
                ->with('success', 'Tạo quyền thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Permission $permission)
    {
        $permission->load('roles');
        return view('admin.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        $permission->load('roles');
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:500'
        ], [
            'name.required' => 'Tên quyền là bắt buộc',
            'name.unique' => 'Tên quyền đã tồn tại',
            'name.max' => 'Tên quyền không được quá 255 ký tự',
            'description.max' => 'Mô tả không được quá 500 ký tự'
        ]);

        try {
            $permission->update([
                'name' => $request->name,
                'description' => $request->description
            ]);

            return redirect()->route('admin.permissions.index')
                ->with('success', 'Cập nhật quyền thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            // Kiểm tra permission có đang được sử dụng không
            if ($permission->roles()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa quyền đang được sử dụng!');
            }

            $permission->delete();

            return redirect()->route('admin.permissions.index')
                ->with('success', 'Xóa quyền thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function bulkCreate()
    {
        return view('admin.permissions.bulk-create');
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'permissions' => 'required|string'
        ], [
            'permissions.required' => 'Danh sách quyền là bắt buộc'
        ]);

        try {
            $lines = explode("\n", $request->permissions);
            $created = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $parts = explode('|', $line);
                $name = trim($parts[0]);
                $description = isset($parts[1]) ? trim($parts[1]) : null;

                if (Permission::where('name', $name)->exists()) {
                    $errors[] = "Quyền '$name' đã tồn tại";
                    continue;
                }

                Permission::create([
                    'name' => $name,
                    'description' => $description
                ]);
                $created++;
            }

            DB::commit();

            $message = "Đã tạo $created quyền thành công!";
            if (!empty($errors)) {
                $message .= " Có " . count($errors) . " lỗi: " . implode(', ', $errors);
            }

            return redirect()->route('admin.permissions.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }
} 