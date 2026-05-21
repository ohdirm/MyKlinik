<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
{
    use Queueable;

    /**
     * @param  array{type: string, title: string, message: string, icon: string, booking_code: string}  $data
     */
    public function __construct(
        public Booking $booking,
        public string $type,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return match ($this->type) {
            'submitted' => [
                'type' => 'submitted',
                'icon' => '📩',
                'title' => 'Pendaftaran Berhasil Dikirim',
                'message' => "Booking {$this->booking->booking_code} sedang menunggu konfirmasi admin. Kami juga telah mengirim detail ke email Anda.",
                'booking_code' => $this->booking->booking_code,
                'booking_id' => $this->booking->id,
            ],
            'confirmed' => [
                'type' => 'confirmed',
                'icon' => '✅',
                'title' => 'Booking Dikonfirmasi!',
                'message' => "Booking {$this->booking->booking_code} telah dikonfirmasi. Dokter: {$this->booking->doctor->name}, Tanggal: {$this->booking->exam_date->format('d/m/Y')}, No. Antrean: #{$this->booking->queue_number}. Cek email untuk detail lengkap.",
                'booking_code' => $this->booking->booking_code,
                'booking_id' => $this->booking->id,
            ],
            'rejected' => [
                'type' => 'rejected',
                'icon' => '❌',
                'title' => 'Booking Ditolak',
                'message' => "Maaf, booking {$this->booking->booking_code} ditolak. Alasan: {$this->booking->rejection_reason}. Silakan buat booking baru.",
                'booking_code' => $this->booking->booking_code,
                'booking_id' => $this->booking->id,
            ],
            'done' => [
                'type' => 'done',
                'icon' => '🎉',
                'title' => 'Kunjungan Selesai',
                'message' => "Kunjungan Anda ({$this->booking->booking_code}) telah selesai. Terima kasih! Silakan berikan review untuk dokter Anda.",
                'booking_code' => $this->booking->booking_code,
                'booking_id' => $this->booking->id,
            ],
            default => [
                'type' => $this->type,
                'icon' => '🔔',
                'title' => 'Notifikasi',
                'message' => "Update untuk booking {$this->booking->booking_code}.",
                'booking_code' => $this->booking->booking_code,
                'booking_id' => $this->booking->id,
            ],
        };
    }
}
