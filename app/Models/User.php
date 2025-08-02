<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'integer',
    ];

    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    // Kiểm tra user có role không
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    // Kiểm tra user có permission không (thông qua role)
    public function hasPermission($permissionName)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionName) {
            $query->where('name', $permissionName);
        })->exists();
    }

    // Gán role cho user
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role && !$this->hasRole($role->name)) {
            $this->roles()->attach($role->id);
        }

        return $this;
    }

    // Thu hồi role từ user
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }

        return $this;
    }

    // Kiểm tra user có phải admin không
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    // Kiểm tra user có phải super admin không
    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

    // Lấy tất cả permissions của user
    public function getAllPermissions()
    {
        return Permission::whereHas('roles', function ($query) {
            $query->whereIn('roles.id', $this->roles->pluck('id'));
        })->get();
    }

    // Lấy danh sách tên permissions
    public function getPermissionNames()
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    // Kiểm tra user có bất kỳ permission nào trong danh sách không
    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    // Kiểm tra user có tất cả permissions trong danh sách không
    public function hasAllPermissions($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function point(): HasOne
    {
        return $this->hasOne(Point::class);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function couponUsers(): HasMany
    {
        return $this->hasMany(CouponUser::class);
    }

    /**
     * Lấy điểm hiện tại của user
     */
    public function getCurrentPoints(): int
    {
        return $this->point?->total_points ?? 0;
    }

    /**
     * Lấy level VIP của user
     */
    public function getVipLevel(): string
    {
        return $this->point?->vip_level ?? 'Bronze';
    }

    /**
     * Kiểm tra user có đủ điểm để đổi voucher không
     */
    public function hasEnoughPoints(int $requiredPoints): bool
    {
        return $this->getCurrentPoints() >= $requiredPoints;
    }
}
