<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin.access');
    }

    public function index(Request $request)
    {
        $query = User::with('roles');
        
        // Tìm kiếm theo tên hoặc email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo role
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('id', 'desc')->paginate(15);
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show($id)
    {
        $user = User::with('roles.permissions')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:0,1',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ], [
            'name.required' => 'Tên là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
        ];

        // Chỉ update password nếu có nhập
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Cập nhật roles
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles ?? []);
        }

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành viên thành công');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Không cho phép xóa chính mình
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa chính mình');
        }

        // Xóa các quan hệ trước
        $user->roles()->detach();
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Xóa thành viên thành công');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Không cho phép thay đổi trạng thái của chính mình
        if ($user->id == auth()->id()) {
            return response()->json(['error' => 'Không thể thay đổi trạng thái của chính mình'], 400);
        }
        
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();
        
        return response()->json([
            'success' => true,
            'status' => $user->status,
            'message' => 'Đã cập nhật trạng thái thành viên'
        ]);
    }

    public function roles($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('admin.users.roles', compact('user', 'roles', 'userRoles'));
    }

    public function updateRoles(Request $request, $id)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        $user = User::findOrFail($id);
        
        try {
            $user->roles()->sync($request->roles ?? []);
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Cập nhật vai trò cho thành viên thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function permissions($id)
    {
        $user = User::with(['roles.permissions'])->findOrFail($id);
        $allPermissions = [];
        
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $allPermissions[$permission->id] = $permission;
            }
        }
        
        return view('admin.users.permissions', compact('user', 'allPermissions'));
    }
}
