# MyKlinik911

Sistem Pendaftaran Online Klinik — Dibangun dengan Laravel 12, Tailwind CSS v4, dan Alpine.js.

## Fitur

- **Pendaftaran Online** — Pasien dapat mendaftar tanpa akun, memilih dokter & jadwal
- **Status Dokter Real-time** — Pantau ketersediaan dokter dengan auto-refresh
- **Panel Admin** — Dashboard, kelola booking, status dokter, CRUD dokter & jadwal
- **Notifikasi WhatsApp** — Konfirmasi booking langsung via WhatsApp
- **Wilayah Indonesia** — Cascading select provinsi → kabupaten → kecamatan → kelurahan

## Tech Stack

- PHP 8.2 + Laravel 12
- Tailwind CSS v4 (via @tailwindcss/vite)
- Alpine.js (CDN)
- MySQL
- Vite

## Setup

```bash
# 1. Clone & install
git clone <repo-url>
cd MyKlinik
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
APP_NAME=MyKlinik911
DB_CONNECTION=mysql
DB_DATABASE=myklinik911
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 3. Database
php artisan migrate
php artisan db:seed

# 4. Wilayah Indonesia (opsional — membutuhkan laravolt/indonesia)
composer require laravolt/indonesia
php artisan vendor:publish --provider="Laravolt\Indonesia\ServiceProvider"
php artisan laravolt:indonesia:seed

# 5. Storage link
php artisan storage:link

# 6. Build & serve
npm run build
php artisan serve
```

Atau jalankan development mode:
```bash
composer run dev
```

## Login Admin

| Email | Password |
|---|---|
| admin@myklinik911.com | admin123 |

## Struktur Utama

```
app/Http/Controllers/
├── HomeController.php
├── BookingController.php
├── DoctorStatusController.php
├── ApiController.php
├── WilayahController.php
├── AuthController.php
└── Admin/
    ├── DashboardController.php
    ├── BookingController.php
    ├── DoctorStatusController.php
    ├── DoctorController.php
    └── ScheduleController.php

resources/views/
├── layouts/  (app.blade.php, admin.blade.php)
├── home/     (index)
├── booking/  (index)
├── status/   (index)
├── auth/     (login)
└── admin/    (dashboard, bookings, doctor-status, doctors, schedules)
```

## License

MIT
