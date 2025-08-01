<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Events\UserActivity;

class AuthenticationController extends Controller
{
    public function login() {
        return view('client.auth.login');
    }

    public function register() {
        return view('client.auth.register');
    }

    public function postLogin(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ], [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống'
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();
            
            // Dispatch UserActivity event for admin notification
            event(new UserActivity($user, 'login', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]));
            
            // Redirect theo role
            if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công! Chào mừng đến admin panel.');
            } elseif ($user->hasRole('staff')) {
                return redirect()->route('admin.dashboard')->with('info', 'Đăng nhập thành công! Bạn là nhân viên - có thể vào admin nhưng không được quản lý user.');
            } else {
                // Customer hoặc role khác
                return redirect('/')->with('success', 'Đăng nhập thành công!');
            }
        } else {
            return redirect()->back()->with('error', 'Thông tin đăng nhập không chính xác');
        }
    }

    public function postRegister(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:18', 'unique:users'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ], [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'password.required' => 'Mật khẩu không được để trống',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        // Gán role user cho user đăng ký thông thường
        $customerRole = \App\Models\Role::where('name', 'user')->first();
        if ($customerRole) {
            $user->assignRole($customerRole);
        }
        Auth::login($user);
        return redirect()->route('client.home')->with('success', 'Đăng ký thành công!');
    }

    // Google OAuth
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('email', $user->email)->first();
            
            if($finduser){
                Auth::login($finduser);
                
                // Redirect theo role của user
                if ($finduser->hasRole('admin') || $finduser->hasRole('super_admin')) {
                    return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập Google thành công! Chào mừng đến admin panel.');
                } elseif ($finduser->hasRole('staff')) {
                    return redirect()->route('admin.dashboard')->with('info', 'Đăng nhập Google thành công! Bạn là nhân viên.');
                } else {
                    // Customer hoặc role khác
                    return redirect()->route('client.home')->with('success', 'Đăng nhập Google thành công!');
                }
            } else {
                // Tạo user mới từ Google - tạo phone unique để tránh constraint
                $uniquePhone = 'g' . time() . 'x' . substr(md5($user->email), 0, 5);
                
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $uniquePhone, // Tạo phone unique thay vì để trống
                    'password' => Hash::make('google_login_' . Str::random(16)),
                    'email_verified_at' => now(),
                ]);
                
                // Gán role user cho user mới từ Google
                $userRole = \App\Models\Role::where('name', 'user')->first();
                if ($userRole) {
                    $newUser->assignRole($userRole);
                }
                
                Auth::login($newUser);
                return redirect()->route('client.home')->with('success', 'Đăng ký Google thành công! Chào mừng bạn đến với Winstar!');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Có lỗi xảy ra khi đăng nhập Google: ' . $e->getMessage());
        }
    }

    public function sendResetLink(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ], [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout() {
        $user = Auth::user();
        
        // Dispatch UserActivity event for admin notification
        if ($user) {
            event(new UserActivity($user, 'logout', [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]));
        }
        
        Auth::logout();
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }
}
