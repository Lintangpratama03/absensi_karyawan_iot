<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index', [
            'title' => 'Login'
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $userRole = Auth::user()->role;

            switch ($userRole) {
                case 'admin':
                    return redirect()->intended('/dashboard');
                    break;
                case 'karyawan':
                    return redirect()->intended('/employee/dashboard');
                    break;
                default:
                    return redirect()->intended('/');
                    break;
            }
        }

        return back()->with('loginError', 'Username atau password salah!');
    }


    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }
}
