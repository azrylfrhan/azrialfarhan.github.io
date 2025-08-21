<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEntryNotification extends Notification
{
    use Queueable;

    // Properti untuk menyimpan data yang dikirim dari controller
    protected $entry; // Ini bisa berisi objek PermohonanSurat atau Pengaduan
    protected $type;  // Ini akan berisi teks 'permohonan' atau 'pengaduan'

    /**
     * Membuat instance notifikasi baru.
     *
     * @param mixed $entry Objek data (PermohonanSurat atau Pengaduan)
     * @param string $type Tipe entri ('permohonan' atau 'pengaduan')
     * @return void
     */
    public function __construct($entry, $type)
    {
        $this->entry = $entry;
        $this->type = $type;
    }

    /**
     * Menentukan channel pengiriman notifikasi.
     * Kita hanya menggunakan 'database' untuk menyimpannya di tabel notifikasi.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Mengubah notifikasi menjadi format array untuk disimpan di database.
     * Data inilah yang akan kita panggil dan tampilkan di navbar.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        // Jika notifikasi ini untuk 'permohonan' baru
        if ($this->type === 'permohonan') {
            return [
                'message'  => 'Permohonan surat baru dari ' . $this->entry->penduduk->nama,
                'icon'     => 'fas fa-file-alt text-white', // Ikon untuk notifikasi surat
                'bg_color' => 'bg-primary',                 // Warna latar ikon
                'link'     => route('surat.index'),         // Link tujuan saat notifikasi diklik
            ];
        }

        // Jika notifikasi ini untuk 'pengaduan' baru
        if ($this->type === 'pengaduan') {
            return [
                'message'  => 'Pengaduan baru dari ' . $this->entry->penduduk->nama,
                'icon'     => 'fas fa-bullhorn text-white', // Ikon untuk notifikasi pengaduan
                'bg_color' => 'bg-danger',                  // Warna latar ikon
                'link'     => route('pengaduan.index'),     // Link tujuan saat notifikasi diklik
            ];
        }

        // Default jika tipe tidak dikenali
        return [];
    }
}
