<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected string $token;
    protected string $apiUrl;

    public function __construct()
    {
        $this->token  = config('services.fonnte.token', '');
        $this->apiUrl = config('services.fonnte.url', 'https://api.fonnte.com/send');
    }

    /**
     * Kirim pesan WhatsApp ke satu nomor.
     *
     * @param  string  $phone   Nomor tujuan format 628xxxx
     * @param  string  $message Isi pesan
     * @return bool
     */
    public function sendMessage(string $phone, string $message): bool
    {
        if (empty($this->token) || $this->token === 'isi_token_dari_dashboard_fonnte') {
            Log::warning('WhatsappService: FONNTE_TOKEN belum dikonfigurasi.');
            return false;
        }

        if (empty($phone)) {
            Log::warning('WhatsappService: Nomor WA tujuan kosong, pesan tidak dikirim.');
            return false;
        }

        // Normalisasi nomor: hilangkan karakter non-digit
        $phone = preg_replace('/\D/', '', $phone);

        // Ganti awalan 0 dengan 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => $this->token,
                ])
                ->asForm()
                ->post($this->apiUrl, [
                    'target'      => $phone,
                    'message'     => $message,
                    'countryCode' => '62',
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false) === true) {
                Log::info("WhatsappService: Pesan berhasil dikirim ke {$phone}");
                return true;
            }

            Log::warning("WhatsappService: Gagal kirim ke {$phone}. Response: " . json_encode($body));
            return false;

        } catch (\Exception $e) {
            Log::error("WhatsappService: Exception saat kirim ke {$phone}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim pesan ke banyak nomor sekaligus.
     *
     * @param  array<string>  $phones  Daftar nomor WA
     * @param  string         $message Isi pesan
     */
    public function sendToMany(array $phones, string $message): void
    {
        foreach ($phones as $phone) {
            if (!empty($phone)) {
                $this->sendMessage($phone, $message);
            }
        }
    }
}
