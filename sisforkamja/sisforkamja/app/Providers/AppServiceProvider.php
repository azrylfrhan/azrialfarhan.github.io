<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Cek dulu apakah ada user yang sedang login
            if (Auth::check()) {
                $user = Auth::user();
                // Ambil 5 notifikasi terbaru yang belum dibaca
                $unreadNotifications = $user->unreadNotifications()->limit(5)->get();
                // Hitung total notifikasi yang belum dibaca
                $notificationCount = $user->unreadNotifications()->count();
                
                // Kirim variabel-variabel ini ke semua view
                $view->with(compact('unreadNotifications', 'notificationCount'));
            }
        });
    }
}
                