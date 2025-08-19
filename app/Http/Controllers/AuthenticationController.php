<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Events\UserActivity;
use App\Mail\EmailVerificationMail;
use App\Services\EmailService;

class AuthenticationController extends Controller
{
    public function login() {
        return view('client.auth.login');
    }

    public function register() {
        return view('client.auth.register');
    }

    public function postLogin(Request $request) {
        // Debug session
        Log::info('Login attempt for email: ' . $request->email);
        Log::info('Session ID: ' . session()->getId());
        Log::info('CSRF Token: ' . $request->input('_token'));
        
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
            
            // Kiểm tra email verification cho user thường
            if (!$user->email_verified_at && !$user->hasRole('admin') && !$user->hasRole('staff')) {
                // Tạo mã xác nhận mới nếu chưa có
                if (!$user->email_verification_code) {
                    $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                    $user->update([
                        'email_verification_code' => $verificationCode,
                        'email_verification_expires_at' => now()->addMinutes(15),
                    ]);
                    
                                            // Gửi email xác nhận với email service
                        $emailService = new EmailService();
                        $emailSent = $emailService->sendVerificationEmail($user->email, $verificationCode, $user->name);
                        
                        if (!$emailSent) {
                            Log::error('Không thể gửi email verification cho user: ' . $user->email);
                        }
                }
                
                // Lưu session và chuyển đến trang xác nhận
                session(['pending_verification_user_id' => $user->id]);
                Auth::logout();
                return redirect()->route('verify.email')->with('error', 'Vui lòng xác nhận email trước khi đăng nhập');
            }
            
            // Dispatch UserActivity event for admin notification (disabled for now)
            // event(new UserActivity($user, 'login', [
            //     'ip' => $request->ip(),
            //     'user_agent' => $request->userAgent()
            // ]));
            
            // Redirect theo role
            if ($user->hasRole('admin')) {
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
            'billing_city' => ['required', 'string', 'max:255'],
            'billing_district' => ['required', 'string', 'max:255'],
            'billing_ward' => ['required', 'string', 'max:255'],
            'billing_address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ], [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'billing_city.required' => 'Vui lòng chọn Tỉnh/Thành phố',
            'billing_district.required' => 'Vui lòng chọn Quận/Huyện',
            'billing_ward.required' => 'Vui lòng chọn Phường/Xã',
            'billing_address.required' => 'Vui lòng nhập địa chỉ chi tiết',
            'password.required' => 'Mật khẩu không được để trống',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);

        // Tạo địa chỉ đầy đủ
        $fullAddress = $request->billing_address . ', ' . $request->billing_ward . ', ' . $request->billing_district . ', ' . $request->billing_city;

        // Tạo mã xác nhận email
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $fullAddress,
            'password' => Hash::make($request->password),
            'email_verification_code' => $verificationCode,
            'email_verification_expires_at' => now()->addMinutes(15),
        ]);
        
        // Gán role user cho user đăng ký thông thường
        $customerRole = \App\Models\Role::where('name', 'user')->first();
        if ($customerRole) {
            $user->assignRole($customerRole);
        }
        
        // Gửi email xác nhận với email service
        $emailService = new EmailService();
        $emailSent = $emailService->sendVerificationEmail($user->email, $verificationCode, $user->name);
        
        if (!$emailSent) {
            Log::error('Không thể gửi email verification cho user: ' . $user->email);
        }
        
        // Lưu thông tin user vào session để xác nhận email
        session(['pending_verification_user_id' => $user->id]);
        
        return redirect()->route('verify.email')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản.');
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
                // Nếu user đăng nhập bằng Google, tự động xác nhận email
                if (!$finduser->email_verified_at) {
                    $finduser->update([
                        'email_verified_at' => now(),
                        'email_verification_code' => null,
                        'email_verification_expires_at' => null,
                    ]);
                }
                
                Auth::login($finduser);
                
                // Redirect theo role của user
                if ($finduser->hasRole('admin')) {
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
                
                // Tạo mã xác nhận cho user mới
                $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $uniquePhone, // Tạo phone unique thay vì để trống
                    'password' => Hash::make('google_login_' . Str::random(16)),
                    'email_verification_code' => $verificationCode,
                    'email_verification_expires_at' => now()->addMinutes(15),
                    // KHÔNG set email_verified_at ngay lập tức
                ]);
                
                // Gán role user cho user mới từ Google
                $userRole = \App\Models\Role::where('name', 'user')->first();
                if ($userRole) {
                    $newUser->assignRole($userRole);
                }
                
                // Gửi email xác nhận
                try {
                    Mail::to($newUser->email)->send(new EmailVerificationMail($verificationCode, $newUser->name));
                } catch (\Exception $e) {
                    Log::error('Lỗi gửi email verification cho Google user: ' . $e->getMessage());
                }
                
                // Lưu session và chuyển đến trang xác nhận
                session(['pending_verification_user_id' => $newUser->id]);
                return redirect()->route('verify.email')->with('success', 'Đăng ký Google thành công! Vui lòng kiểm tra email để xác nhận tài khoản.');
            }
            
        } catch (\Exception $e) {
            Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Có lỗi xảy ra khi đăng nhập Google. Vui lòng thử lại.');
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

                try {
            event(new PasswordReset($user));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast PasswordReset event: ' . $e->getMessage());
        }
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout() {
        $user = Auth::user();
        
        // Dispatch UserActivity event for admin notification (disabled for now)
        // if ($user) {
        //     event(new UserActivity($user, 'logout', [
        //         'ip' => request()->ip(),
        //         'user_agent' => request()->userAgent()
        //     ]));
        // }
        
        Auth::logout();
        return redirect()->route('login');
    }

    public function showVerifyEmail() {
        if (!session('pending_verification_user_id')) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin xác nhận email');
        }
        return view('client.auth.verify-email');
    }

    public function verifyEmail(Request $request) {
        $request->validate([
            'verification_code' => ['required', 'string', 'size:6'],
        ], [
            'verification_code.required' => 'Mã xác nhận không được để trống',
            'verification_code.size' => 'Mã xác nhận phải có 6 số',
        ]);

        $userId = session('pending_verification_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Phiên xác nhận đã hết hạn');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin người dùng');
        }

        // Kiểm tra mã xác nhận
        if ($user->email_verification_code !== $request->verification_code) {
            return redirect()->back()->with('error', 'Mã xác nhận không chính xác');
        }

        // Kiểm tra thời gian hết hạn
        if (now()->isAfter($user->email_verification_expires_at)) {
            return redirect()->back()->with('error', 'Mã xác nhận đã hết hạn. Vui lòng yêu cầu mã mới');
        }

        // Xác nhận email thành công
        $user->update([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_expires_at' => null,
        ]);

        // Xóa session
        session()->forget('pending_verification_user_id');

        // Đăng nhập user
        Auth::login($user);
        
        // Debug log
        Log::info('Email verification completed', [
            'user_id' => $user->id,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'auth_check' => Auth::check(),
            'auth_user_id' => Auth::id()
        ]);

        // Kiểm tra xem user có phải Google user không
        $isGoogleUser = $user->isGoogleUser();
        $successMessage = $isGoogleUser 
            ? 'Xác nhận email thành công! Chào mừng bạn đến với Winstar! Tài khoản Google của bạn đã được kích hoạt.'
            : 'Xác nhận email thành công! Chào mừng bạn đến với Winstar!';
            
        return redirect()->route('client.home')->with('success', $successMessage);
    }

    public function resendVerification() {
        // Debug session
        if (request()->ajax()) {
            Log::info('AJAX request to resend verification');
            Log::info('Session data: ' . json_encode(session()->all()));
            Log::info('Session ID: ' . session()->getId());
            Log::info('Pending user ID: ' . session('pending_verification_user_id'));
        }
        
        $userId = session('pending_verification_user_id');
        if (!$userId) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phiên xác nhận đã hết hạn'
                ]);
            }
            return redirect()->route('login')->with('error', 'Phiên xác nhận đã hết hạn');
        }

        $user = User::find($userId);
        if (!$user) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin người dùng'
                ]);
            }
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin người dùng');
        }

        // Tạo mã xác nhận mới
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'email_verification_code' => $verificationCode,
            'email_verification_expires_at' => now()->addMinutes(15),
        ]);

        // Gửi email xác nhận mới với email service
        $emailService = new EmailService();
        $emailSent = $emailService->sendVerificationEmail($user->email, $verificationCode, $user->name);
        
        if ($emailSent) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã gửi lại mã xác nhận. Vui lòng kiểm tra email'
                ]);
            }
            return redirect()->route('verify.email')->with('success', 'Đã gửi lại mã xác nhận. Vui lòng kiểm tra email');
        } else {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể gửi email. Vui lòng thử lại sau'
                ]);
            }
            return redirect()->route('verify.email')->with('error', 'Không thể gửi email. Vui lòng thử lại sau');
        }
    }
}
