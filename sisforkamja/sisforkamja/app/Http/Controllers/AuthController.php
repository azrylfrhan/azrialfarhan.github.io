<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.auth.login');
    }



    public function authenticate(Request $request)
    {
        // Validasi field (tidak ada perubahan)
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        // Percobaan login (tidak ada perubahan)
        if (Auth::attempt(['name' => $credentials['name'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            // Ambil data user yang sedang login
            $user = Auth::user(); 

            // Pengecekan status akun (tidak ada perubahan)
            if ($user->status == 'submitted') {
                return back()->withErrors([
                    'name' => 'Akun anda masih menunggu persetujuan',
                ]);
            } elseif ($user->status == 'rejected') {
                return back()->withErrors([
                    'name' => 'Akun anda ditolak admin',
                ]);
            }

            // ==========================================================
            // === MULAI LOGIKA PENGALIHAN BERDASARKAN PERAN (ROLE) ===
            // ==========================================================
            $role = $user->role->name;

            if ($role == 'Admin') {
                return redirect()->intended('/dashboard');
            } elseif ($role == 'Kepala Lingkungan') {
                return redirect()->intended('/dashboard-kepala-lingkungan');
            }
            
            // Fallback untuk peran lain (misalnya Warga) atau jika tidak ada peran
            return redirect()->intended('/home');
            // ========================================================

        }

        // Jika login gagal (tidak ada perubahan)
        return back()->withErrors([
            'name' => 'Terjadi kesalahan, periksa kembali username atau password anda.',
        ])->onlyInput('name');
    }

    public function logout(Request $request)
    {
        if (!Auth::check()){
            return redirect('/administrasi');
        }
        
        // ==========================================================
        // === PERUBAHAN DI SINI ===
        // ==========================================================
        // 1. Simpan dulu peran pengguna sebelum di-logout
        $userRole = Auth::user()->role->name;

        // 2. Lakukan proses logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 3. Arahkan berdasarkan peran yang sudah disimpan
        if ($userRole == 'Admin' || $userRole == 'Kepala Lingkungan') {
            // Jika Admin atau Kepling, kembali ke login admin
            return redirect('/');
        } else {
            // Jika bukan (berarti Warga), kembali ke portal publik
            return redirect('/login-warga');
        }
        // ==========================================================
    }
}

