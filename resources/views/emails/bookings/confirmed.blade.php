<x-mail::message>
# Konfirmasi Pendaftaran - MyKlinik911

Halo **{{ $booking->patient_name }}**,

Pendaftaran Anda telah **DIKONFIRMASI**. Berikut adalah detail jadwal pemeriksaan Anda:

<x-mail::panel>
**Detail Booking:**
- Kode Booking: **{{ $booking->booking_code }}**
- Nomor Antrean: **{{ $booking->queue_number }}**

**Detail Jadwal:**
- Dokter: **{{ $booking->doctor->name }}**
- Tanggal: **{{ $booking->exam_date->format('d F Y') }}**
- Jam: **{{ $booking->schedule->time_range }}**
</x-mail::panel>

### 💡 Informasi Penting:
1. Silakan datang **15 menit** sebelum jadwal pemeriksaan untuk proses verifikasi.
2. Tunjukkan email ini atau pesan WhatsApp konfirmasi kepada petugas pendaftaran di klinik.
3. Jika Anda berhalangan hadir, harap hubungi kami sesegera mungkin.

Terima kasih atas kepercayaan Anda kepada MyKlinik911.

Salam sehat,<br>
**{{ config('app.name') }}**
</x-mail::message>
