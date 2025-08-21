<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdministrasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LacakController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\WargaAuthController;
use App\Http\Controllers\KepalaLingkungan\DashboardController as KepalaLingkunganDashboard;
use Illuminate\Support\Facades\Route;


// Rute Publik (Login, Halaman Utama Administrasi, dll)
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::controller(AdministrasiController::class)->prefix('administrasi')->name('administrasi.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/pengaduan', 'pengaduan')->name('pengaduan');
    Route::get('/urus-surat', 'surat')->name('surat');
    Route::post('/urus-surat', 'store')->name('store');
    Route::get('/lacak', [LacakController::class, 'lacak'])->name('lacak');
});

Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
Route::get('/get-nama/{nik}', [PendudukController::class, 'getNamaByNik']);
Route::get('/api/jenis-surat/{id}/persyaratan', [SuratController::class, 'getPersyaratan']);

Route::get('/registrasi', [WargaAuthController::class, 'showRegisterForm'])->name('warga.register.form');
Route::post('/registrasi', [WargaAuthController::class, 'register'])->name('warga.register.submit');

// Rute untuk menampilkan form dan memproses login warga
Route::get('/login-warga', [WargaAuthController::class, 'showLoginForm'])->name('warga.login.form');
Route::post('/login-warga', [WargaAuthController::class, 'authenticate'])->name('warga.login.submit');

// Grup utama untuk semua halaman yang HANYA bisa diakses setelah login
Route::middleware('auth')->group(function () {

    // --- Rute KHUSUS untuk Admin ---
    Route::middleware('role:Admin')->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('admin', AdminController::class);
        Route::put('/pengaduan/{pengaduan}', [PengaduanController::class, 'update'])->name('pengaduan.update');
        Route::get('/arsip-surat', [SuratController::class, 'arsip'])->name('surat.arsip');

        Route::controller(PendudukController::class)->prefix('penduduk')->name('penduduk.')->group(function () {
            Route::get('/export', 'export')->name('export');
            Route::post('/import', 'import')->name('import');
            Route::get('/template/download', 'downloadTemplate')->name('template.download');
        });
        Route::resource('penduduk', PendudukController::class)->except(['index']);
        Route::get('/persetujuan-pengguna', [AdminController::class, 'showApprovalPage'])->name('admin.approval');
        Route::put('/persetujuan-pengguna/{id}', [AdminController::class, 'approveUser'])->name('admin.approve.user');

        Route::controller(SuratController::class)->group(function() {
            Route::get('/surat', 'index')->name('surat.index');
            Route::put('/surat/{id}', 'editModal')->name('surat.update');
            Route::get('/surat/{id}/generate', 'generateSurat')->name('surat.generate');

            // ===================================================================
            // === PERBAIKAN DI SINI: Rute Jenis Surat Didefinisikan Manual ===
            // ===================================================================
            Route::prefix('jenis-surat')->name('jenis-surat.')->group(function() {
                Route::get('/', 'jenis_surat')->name('index'); // Menampilkan daftar
                Route::post('/', 'store')->name('store'); // Menyimpan data baru
                Route::put('/{id}', 'update')->name('update'); // Mengupdate data
                Route::delete('/{id}', 'destroy')->name('destroy'); // Menghapus data
            });
            // ===================================================================
        });
    });

    // --- Rute KHUSUS untuk Kepala Lingkungan ---
    Route::middleware('role:Kepala Lingkungan')->group(function() {
        Route::get('/dashboard-kepala-lingkungan', [KepalaLingkunganDashboard::class, 'index'])->name('kepala-lingkungan.dashboard');
    });

    // --- Rute untuk Admin DAN Kepala Lingkungan ---
    Route::middleware('role:Admin,Kepala Lingkungan')->group(function () {
        Route::get('/penduduk', [PendudukController::class, 'index'])->name('penduduk.index');
        Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');

        Route::controller(AdminController::class)->group(function() {
            Route::put('/profile/{id}', 'change_profile')->name('profile.update');
            Route::put('/change-password/{id}', 'change_password')->name('password.update');
        });
    });

    // Rute lain yang bisa diakses semua user yang login
    Route::get('/notifications/{id}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});


// Rute untuk tes prediksi (sebaiknya dihapus atau dilindungi juga)
// Route::get('/prediksi', [PrediksiController::class, 'showForm']);
// Route::post('/prediksi', [PrediksiController::class, 'hitungPrediksi']);