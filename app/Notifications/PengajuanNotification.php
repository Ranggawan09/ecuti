<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $message;
    private $namaAplikasi;

    public function __construct($message, $namaAplikasi)
    {
        $this->message = $message;
        $this->namaAplikasi = $namaAplikasi;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line($this->message)
                    ->line('Nama Aplikasi: ' . $this->namaAplikasi)
                    ->action('Lihat Pengajuan', url('/admin/pengajuan/daftar-pengajuan'))
                    ->line('Silahkan untuk segera melakukan konfirmasi pengajuan');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'nama_aplikasi' => $this->namaAplikasi,
        ];
    }
}