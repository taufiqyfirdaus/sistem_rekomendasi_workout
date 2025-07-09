<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect sesuai role
            if (auth()->user()->hasRole('admin')) {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'login' => 'Username atau password salah.'
        ])->withInput();
    }

    
     public function showRegister()
        {
            return view('auth.register');
        }

        public function register(Request $request) {
        $validated = $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'jenis_kelamin' => $validated['jenis_kelamin'],
        ]);

        $user->assignRole('user'); // default role

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout');
    }
}