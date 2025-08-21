<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait SendsWhatsApp
{
    /**
     * Method untuk mengirim pesan WhatsApp via Fonnte.
     * Dapat digunakan di controller mana pun yang memakai trait ini.
     */
    private function sendWhatsAppNotification($target, $message)
    {
        $apiKey = env('FONNTE_API_KEY');

        if (!$apiKey) {
            Log::error('Fonnte API Key tidak ditemukan di file .env');
            return;
        }

        // Format nomor telepon: ganti awalan 0 dengan 62
        if (substr($target, 0, 1) == '0') {
            $target = '62' . substr($target, 1);
        }
        
        try {
            Http::withHeaders([
                'Authorization' => $apiKey
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);
        } catch (\Exception $e) {
            // Jika ada masalah koneksi, catat di log
            Log::error('Error koneksi ke Fonnte: ' . $e->getMessage());
        }
    }
}