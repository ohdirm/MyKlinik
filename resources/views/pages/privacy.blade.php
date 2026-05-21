@extends('layouts.app')
@section('title', 'Kebijakan Privasi — MyKlinik911')
@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-12 px-4 transition-colors duration-200">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-8 md:p-12">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Kebijakan Privasi</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8 italic text-right">Terakhir diperbarui: {{ now()->format('d F Y') }}</p>

        <div class="prose prose-teal dark:prose-invert max-w-none space-y-6 text-gray-700 dark:text-gray-300 leading-relaxed">
            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2 mb-3">1. Informasi yang Kami Kumpulkan</h2>
                <p>Kami mengumpulkan informasi identitas pribadi (Nama, NIK, No. HP, Alamat) dan informasi kesehatan dasar (keluhan penyakit) yang Anda berikan secara sukarela untuk keperluan pendaftaran medis.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2 mb-3">2. Penggunaan Informasi</h2>
                <p>Data Anda digunakan semata-mata untuk:</p>
                <ul class="list-disc list-inside space-y-2">
                    <li>Memvalidasi identitas pasien di lokasi klinik.</li>
                    <li>Mengelola urutan antrean dokter.</li>
                    <li>Mengirimkan notifikasi status booking melalui WhatsApp.</li>
                    <li>Membantu dokter memahami keluhan pasien sebelum konsultasi.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2 mb-3">3. Keamanan Data</h2>
                <p>Kami menerapkan prosedur keamanan fisik dan elektronik untuk melindungi data Anda. Data medis pasien bersifat rahasia dan hanya dapat diakses oleh staf medis yang berwenang di MyKlinik911.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2 mb-3">4. Hak Anda</h2>
                <p>Anda berhak untuk melihat, memperbarui, atau meminta penghapusan akun dan data pribadi Anda jika tidak lagi menggunakan layanan kami, melalui permintaan tertulis ke admin klinik.</p>
            </section>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('register') }}" class="btn-primary px-8">Saya Mengerti, Kembali ke Pendaftaran</a>
        </div>
    </div>
</div>
@endsection
