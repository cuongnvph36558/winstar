<?php

namespace App\Http\Controllers\Client;

use App\Models\Post;
use App\Models\User;
use App\Models\Order;
use App\Models\Banner;
use App\Models\Feature;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\AboutPage;
use App\Models\OrderDetail;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Service;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class HomeController extends Controller
{

    public function index()
    {
        $banners = Banner::where('status', 1)
            ->orderByDesc('id')
            ->get();

        $productBestSeller = OrderDetail::with('product')
            ->whereHas('order')
            ->whereHas('product')
            ->orderByDesc('quantity')
            ->limit(8)
            ->get();

        $feature = Feature::with('items')
            ->where('status', 'active')
            ->first() ?? new \App\Models\Feature(['title' => 'Không có tiêu đề']);

        $latestPosts = Post::with('author')
            ->withCount('comments')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $mainVideo = Video::latest()->first();

        $productsFavorite = Product::withCount('favorites')
            ->orderByDesc('favorites_count')
            ->orderByDesc('view')
            ->limit(8)
            ->get();

        $services = Service::orderBy('order')->get();

        return view('client.home', compact(
            'banners',
            'productBestSeller',
            'feature',
            'latestPosts',
            'mainVideo',
            'productsFavorite',
            'services'
        ));
    }


    public function contact()
    {
        return view('client.contact.index');
    }

    public function blog()
    {
        return view('client.blog.list-blog');
    }

    public function loginRegister()
    {
        return view('client.auth.login-register');
    }

    public function about()
    {
        $about = AboutPage::first();
        return view('client.about.index', compact('about'));
    }

    public function cart()
    {
        return view('client.cart-checkout.cart');
    }

    public function checkout()
    {
        return view('client.cart-checkout.checkout');
    }

    public function profile()
    {
        if (Auth::check()) {
            $user = Auth::user();
        }
        return view('client.profile.index')->with([
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required' => 'Họ và tên là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'avatar.image' => 'File phải là hình ảnh',
            'avatar.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
            'avatar.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        ]);

        $user = User::where('id', Auth::user()->id);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'ward' => $request->ward
        ];

        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            \Log::info('Avatar upload started in updateProfile');
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            \Log::info('Avatar name: ' . $avatarName);
            
            try {
                $result = $avatar->storeAs('public/avatars', $avatarName);
                \Log::info('Avatar stored at: ' . $result);
                $data['avatar'] = 'avatars/' . $avatarName;
                \Log::info('Avatar path saved: ' . $data['avatar']);
            } catch (\Exception $e) {
                \Log::error('Avatar upload failed: ' . $e->getMessage());
                return redirect()->back()->withErrors(['avatar' => 'Upload ảnh thất bại: ' . $e->getMessage()]);
            }
        }

        $user->update($data);
        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function updateProfileRelaxed(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.string' => 'Họ và tên phải là chuỗi ký tự',
            'email.email' => 'Email không đúng định dạng',
            'avatar.image' => 'File phải là hình ảnh',
            'avatar.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
            'avatar.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        ]);

        $user = User::where('id', Auth::user()->id);

        $data = [];
        
        // Chỉ update những field có giá trị
        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }
        if ($request->filled('email')) {
            $data['email'] = $request->email;
        }
        if ($request->filled('phone')) {
            $data['phone'] = $request->phone;
        }
        if ($request->filled('address')) {
            $data['address'] = $request->address;
        }
        if ($request->filled('city')) {
            $data['city'] = $request->city;
        }
        if ($request->filled('district')) {
            $data['district'] = $request->district;
        }
        if ($request->filled('ward')) {
            $data['ward'] = $request->ward;
        }

        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            Log::info('Avatar upload started in updateProfileRelaxed');
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            Log::info('Avatar name: ' . $avatarName);
            Log::info('File size: ' . $avatar->getSize());
            Log::info('File type: ' . $avatar->getMimeType());
            
            try {
                // Tạo thư mục nếu chưa tồn tại
                $path = storage_path('app/public/avatars');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                    Log::info('Created directory: ' . $path);
                }
                
                $result = $avatar->storeAs('avatars', $avatarName);
                Log::info('Avatar stored at: ' . $result);
                
                // Kiểm tra file có tồn tại không
                $fullPath = storage_path('app/' . $result);
                if (file_exists($fullPath)) {
                    Log::info('File exists at: ' . $fullPath);
                    Log::info('File size on disk: ' . filesize($fullPath));
                } else {
                    Log::error('File does not exist at: ' . $fullPath);
                }
                
                $data['avatar'] = 'avatars/' . $avatarName;
                Log::info('Avatar path saved: ' . $data['avatar']);
            } catch (\Exception $e) {
                Log::error('Avatar upload failed: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                return redirect()->back()->withErrors(['avatar' => 'Upload ảnh thất bại: ' . $e->getMessage()]);
            }
        }

        if (!empty($data)) {
            $user->update($data);
            return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
        }

        return redirect()->back()->with('info', 'Không có thông tin nào được cập nhật');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    }
}


