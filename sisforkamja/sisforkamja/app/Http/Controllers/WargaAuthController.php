<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\penduduk;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class WargaAuthController extends Controller
{
    // Fungsi untuk menampilkan halaman registrasi
    public function showRegisterForm()
    {
        return view('pages.auth.register_warga');
    }

    public function showLoginForm()
    {
        return view('pages.auth.login_warga');
    }

    /**
     * Memproses otentikasi login warga.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'nik' => ['required', 'digits:16'],
            'password' => ['required'],
        ]);

        // Coba login menggunakan NIK dan password
        if (Auth::attempt(['nik' => $credentials['nik'], 'password' => $credentials['password']])) {
            
            $user = Auth::user();

            // PENTING: Cek apakah status akun sudah 'approved'
            if ($user->status !== 'approved') {
                Auth::logout(); // Logout paksa jika belum disetujui
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->with('error', 'Akun Anda belum disetujui oleh Admin atau telah ditolak.');
            }
            
            // Jika disetujui, lanjutkan
            $request->session()->regenerate();
            
            // Arahkan ke halaman utama layanan setelah login
            return redirect()->intended('/administrasi');
        }

        // Jika NIK atau password salah
        return back()->with('error', 'NIK atau Password yang Anda masukkan salah.');
    }

    // Fungsi untuk memproses data registrasi
    public function register(Request $request)
    {
        $validated = $request->validate([
            // Aturan ini akan memeriksa apakah NIK ada di tabel 'penduduks'
            'nik' => 'required|digits:16|exists:penduduks,nik',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Cek apakah user dengan NIK ini sudah pernah mendaftar
        if (User::where('nik', $validated['nik'])->exists()) {
            return back()->withErrors(['nik' => 'NIK ini sudah terdaftar sebagai pengguna. Silakan login.'])->withInput();
        }

        // Ambil data nama dari tabel penduduk
        $penduduk = Penduduk::where('nik', $validated['nik'])->first();

        // Buat user baru dengan status 'pending'
        User::create([
            'name' => $penduduk->nama,
            'email' => strtolower(str_replace(' ', '', $penduduk->nama)) . '@kamja.id', // Email unik dummy
            'nik' => $validated['nik'],
            'password' => Hash::make($validated['password']),
            'role_id' => 3, // ID untuk Warga
            'status' => 'submitted',
        ]);

        return redirect()->route('warga.login.form')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan dari Admin.');
    }
}