<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array'
        ], [
            'name.required' => 'Tên vai trò là bắt buộc',
            'name.unique' => 'Tên vai trò đã tồn tại',
            'name.max' => 'Tên vai trò không được quá 255 ký tự',
            'description.max' => 'Mô tả không được quá 500 ký tự'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            // Gán permissions cho role
            if ($request->has('permissions')) {
                $role->permissions()->attach($request->permissions);
            }

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Tạo vai trò thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $role->load(['permissions', 'users']);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'array'
        ], [
            'name.required' => 'Tên vai trò là bắt buộc',
            'name.unique' => 'Tên vai trò đã tồn tại',
            'name.max' => 'Tên vai trò không được quá 255 ký tự',
            'description.max' => 'Mô tả không được quá 500 ký tự'
        ]);

        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->name,
                'description' => $request->description
            ]);

            // Cập nhật permissions
            $role->permissions()->sync($request->permissions ?? []);

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Cập nhật vai trò thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Role $role)
    {
        try {
            // Kiểm tra role có đang được sử dụng không
            if ($role->users()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa vai trò đang được sử dụng!');
            }

            DB::beginTransaction();

            // Xóa permissions của role
            $role->permissions()->detach();
            
            // Xóa role
            $role->delete();

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Xóa vai trò thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function permissions(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.roles.permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array'
        ]);

        try {
            $role->permissions()->sync($request->permissions ?? []);
            
            return redirect()->route('admin.roles.index')
                ->with('success', 'Cập nhật quyền cho vai trò thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
} 