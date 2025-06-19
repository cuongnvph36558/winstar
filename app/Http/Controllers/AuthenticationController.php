<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    // View login
    public function login() {
        return view('client.auth.login-register');
    }

    public function postLogin(Request $request) {
        $request->validate([
            'phone' => ['required'],
            'password' => ['required']
        ], [
            'phone.required' => 'SĐT không được để trống',
            'password.required' => 'Mật khẩu không được để trống'
        ]);

        if (Auth::attempt([
            'phone' => $request->phone,
            'password' => $request->password
        ])) {
            return redirect()->route('admin.categories');
        } else {
            return redirect()->back();
        }
    }

    

    
}
