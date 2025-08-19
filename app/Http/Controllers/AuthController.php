<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()){
            return back();
        }
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        if (Auth::check()){
            return back();
        }
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email yang dimasukkan tidak valid!',
            'password.required' => 'Password harus diisi'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status == 'submitted') {
                $this->_logout($request);
                return back()->withErrors([
                    'email' => 'Akun anda masih menunggu persetujuan dari admin'
                ]);
            } else if ($user->status == 'rejected') {
                $this->_logout($request);
                return back()->withErrors([
                    'email' => 'Akun anda telah ditolak admin'
                ]);
            }
 
            // return redirect()->intended('dashboard');
            if ($user->role_id == 1) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role_id == 2) {
                return redirect()->route('user.dashboard');
            }
        }
 
        return back()->withErrors([
            'email' => 'Terjadi kesalahan, mohon periksa lagi email atau password anda.',
        ])->onlyInput('email');
    }

    public function registerView()
    {
        if (Auth::check()){
            return back();
        }
        return view ('pages.auth.register');
    }

    public function register(Request $request)
    {
        if (Auth::check()){
            return back();
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' =>  ['required', 'min:8']
        ], [
            'name.required' => 'Nama harus diisi!',
            'email.required' => 'Email yang dimasukkan tidak valid!',
            'email.email' => 'Email yang dimasukkan tidak valid!',
            'password.required' => 'Password harus diisi!'
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = 2;

        $user->saveOrFail();

        return redirect('/')->with('success', 'Registrasi berhasil, mohon menunggu persetujuan admin');
        
    }

    public function _logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    }

    public function logout(Request $request)
    {
        if (!Auth::check()){
            return redirect('/');
        }
    
        $this->_logout($request);
    
        return redirect('/');
    }
}

