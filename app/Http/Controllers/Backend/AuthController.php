<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(){

    }
    public function index(){
        
        // dd(Auth::id());
        if(Auth::id()>0){
            return redirect()->route('dashboard.index');
        }
        return view('backend.auth.login');
    }
    public function login(AuthRequest  $request){
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
            // dd($credentials);
        if (auth()->attempt($credentials)) {
            // Authentication passed...
            return redirect()->route('dashboard.index')->with('success','Dăng nhập thành công');
        }

        return redirect() -> route('auth.admin') -> withErrors([
            'email' => 'Email không đúng hoặc mật khẩu không đúng.',
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.admin')->with('success','Đăng xuất thành công');
    }
}
