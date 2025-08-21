<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Menandai notifikasi sebagai sudah dibaca dan mengarahkan ke link tujuan.
     */
    public function markAsRead($id)
    {
        // Cari notifikasi milik user yang sedang login
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Tandai sebagai sudah dibaca
        $notification->markAsRead();
        
        // Arahkan pengguna ke link asli dari notifikasi tersebut
        return redirect($notification->data['link']);
    }
}
