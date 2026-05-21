@extends('layouts.app')
@section('title', 'Syarat & Ketentuan — MyKlinik911')
@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-12 px-4 transition-colors duration-200">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-8 md:p-12">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Syarat & Ketentuan</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8 italic text-right">Terakhir diperbarui: {{ now()->format('d F Y') }}</p>

        <div class="prose prose-teal dark:prose-invert max-w-none space-y-6 text-gray-700 dark:text-gray-300 leading-relaxed">
            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2 mb-3">1. Layanan Pendaftaran</h2>
                <p>MyKlinik911 menyediakan platform pendaftaran online untuk memudahkan pasien mendapatkan nomor antrean dokter. Pendaftaran tidak menjamin waktu pemeriksaan yang tepat karena kondisi medis pasien lain dapat mempengaruhi waktu layanan.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2 mb-3">2. Kewajiban Pengguna</h2>
                <p>Pasien diwajibkan memberikan data yang akurat (Nama, NIK, No. HP) saat pendaftaran. Ketidaksesuaian data dapat menyebabkan pembatalan antrean di lokasi klinik.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2 mb-3">3. Pembatalan & Keterlambatan</h2>
                <ul class="list-disc list-inside space-y-2">
                    <li>Pasien disarankan hadir 15 menit sebelum estimasi waktu pemeriksaan.</li>
                    <li>Klinik berhak membatalkan antrean jika pasien tidak hadir setelah dipanggil sebanyak 3 kali.</li>
                    <li>Sistem memungkinkan pembatalan booking melalui dashboard pasien maksimal 1 jam sebelum jadwal.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2 mb-3">4. Kebijakan Operasional</h2>
                <p>Dokter dapat berhalangan hadir sewaktu-waktu karena keadaan darurat. Dalam hal ini, klinik akan berusaha menghubungi pasien melalui nomor HP yang terdaftar atau mengalihkan ke dokter pengganti yang tersedia.</p>
            </section>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('register') }}" class="btn-primary px-8">Saya Mengerti, Kembali ke Pendaftaran</a>
        </div>
    </div>
</div>
@endsection
