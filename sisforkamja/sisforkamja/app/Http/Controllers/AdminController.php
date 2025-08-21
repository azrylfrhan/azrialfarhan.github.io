<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    // ===================================================================
    // === BAGIAN 1: FUNGSI UNTUK MANAJEMEN USER (CRUD) ===
    // === Logika ini sudah benar dan tidak diubah. ===
    // ===================================================================

    public function index()
    {
        $users = User::with('role')->get();
        return view('pages.admin.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('pages.admin.create', compact('roles'));
    }

    public function edit(User $admin)
    {
        $roles = Role::all();
        return view('pages.admin.edit', [
            'user' => $admin,
            'roles' => $roles
        ]);
    }

    public function showApprovalPage()
    {
        // Ambil semua user dengan role 'Warga' yang statusnya masih 'submitted'
        $pendingUsers = User::where('role_id', 3)->where('status', 'submitted')->get(); // <-- Diubah menjadi 'submitted'
        return view('pages.admin.approval', compact('pendingUsers'));
    }

    public function approveUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();

        return redirect()->route('admin.approval')->with('success', 'Pengguna berhasil disetujui.');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role_id' => 'required|exists:roles,id',
                'lingkungan' => 'nullable|required_if:role_id,2|string', // Anggap role_id 2 adalah Kepala Lingkungan
            ]);

            $status = 'submitted'; // Nilai default
            if ($request->role_id == 1 || $request->role_id == 2) {
                $status = 'approved';
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'lingkungan' => $request->lingkungan,
                'status' => $status, // <-- Gunakan variabel status yang dinamis
            ]);
            
            // --- AKHIR PERUBAHAN ---

            return redirect()->route('admin.index')->with('success', 'Pengguna baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan pengguna. Pastikan email tidak duplikat. Pesan: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, User $admin)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
                'password' => 'nullable|string|min:8',
                'role_id' => 'required|exists:roles,id',
                'lingkungan' => 'nullable|required_if:role_id,2|string',
            ]);
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->role_id = $request->role_id;
            $admin->lingkungan = $request->role_id == 1 ? null : $request->lingkungan;
            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }
            $admin->save();
            return redirect()->route('admin.index')->with('success', 'Data pengguna berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate data pengguna.')->withInput();
        }
    }

    public function destroy(User $admin)
    {
        try {
            if (Auth::id() == $admin->id) {
                return redirect()->route('admin.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }
            $admin->delete();
            return redirect()->route('admin.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.index')->with('error', 'Gagal menghapus pengguna.');
        }
    }
    
    // ===================================================================
    // === BAGIAN 2: FUNGSI UNTUK MEMPROSES FORM DARI MODAL ===
    // ===================================================================

    /**
     * Memproses perubahan data profil (nama & email) dari modal.
     */
    public function change_profile(Request $request, $id)
    {
        try {
            // Pastikan user hanya bisa mengubah profilnya sendiri
            if (Auth::id() != $id) {
                abort(403, 'Aksi tidak diizinkan.');
            }
            
            $user = User::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            ]);
            
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            // Kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil. Pastikan email tidak duplikat.');
        }
    }

    /**
     * Memproses perubahan password dari modal.
     */
    public function change_password(Request $request, $id)
    {
        try {
            // Pastikan user hanya bisa mengubah passwordnya sendiri
            if (Auth::id() != $id) {
                abort(403, 'Aksi tidak diizinkan.');
            }

            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $user = User::findOrFail($id);
            $user->password = Hash::make($request->password);
            $user->save();

            // Kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Password berhasil diubah.');

        } catch (\Exception $e) {
            // Menggunakan $e->getMessage() untuk memberikan error yang lebih spesifik jika validasi gagal
            return redirect()->back()->with('error', 'Gagal mengubah password. ' . $e->getMessage());
        }
    }
}
