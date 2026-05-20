<?php

namespace Database\Seeders;

use App\Models\HomeSlide;
use Illuminate\Database\Seeder;

class HomeSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HomeSlide::create([
            'image' => 'assets/clinic_bg.png',
            'title' => 'Selamat Datang di<br><span class=\'text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.3)]\'>MyKlinik911</span>',
            'desc' => 'Sistem pendaftaran online untuk konsultasi dengan dokter pilihan Anda. Mudah, cepat, dan terpercaya.',
            'order' => 1
        ]);

        HomeSlide::create([
            'image' => 'assets/clinic_bg2.png',
            'title' => 'Layanan Keluarga &<br><span class=\'text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.3)]\'>Spesialis Anak</span>',
            'desc' => 'Konsultasi medis ramah anak dengan dokter spesialis berpengalaman untuk kenyamanan buah hati Anda.',
            'order' => 2
        ]);

        HomeSlide::create([
            'image' => 'assets/clinic_bg3.png',
            'title' => 'Fasilitas & Diagnostik<br><span class=\'text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.3)]\'>Modern & Presisi</span>',
            'desc' => 'Didukung oleh teknologi medis terkini untuk hasil diagnosis yang cepat, akurat, dan terpercaya.',
            'order' => 3
        ]);
    }
}
