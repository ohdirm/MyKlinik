<x-mail::message>
# Pendaftaran Berhasil Diterima

Halo **{{ $booking->patient_name }}**,

Pendaftaran online Anda telah kami terima dan saat ini sedang menunggu konfirmasi dari admin MyKlinik911.

<x-mail::panel>
**Ringkasan Pendaftaran:**
- Kode Booking: **{{ $booking->booking_code }}**
- Dokter: **{{ $booking->doctor->name }}**
- Tanggal: **{{ $booking->exam_date->format('d F Y') }}**
- Jam: **{{ $booking->schedule->time_range }}**
</x-mail::panel>

### 🕒 Anda akan menerima pemberitahuan berikutnya jika:
1. Pendaftaran telah **DIKONFIRMASI** (Jadwal tersedia).
2. Pendaftaran **DITOLAK** (Jika ada kendala teknis atau jadwal dokter berubah).

Anda dapat memantau status antrean Anda secara real-time melalui Dashboard Pasien di website kami.

<x-mail::button :url="config('app.url') . '/antrean-saya'">
Cek Status Antrean Saya
</x-mail::button>

Terima kasih telah memilih MyKlinik911.

Salam sehat,<br>
**{{ config('app.name') }}**
</x-mail::message>
