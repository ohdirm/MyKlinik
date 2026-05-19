<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #1f2937; text-align: center;">Verifikasi Email Anda</h2>
        <p style="color: #4b5563; font-size: 16px;">
            Halo! Terima kasih telah mendaftar di MyKlinik911. Untuk melanjutkan, silakan masukkan kode verifikasi 6 digit berikut pada halaman verifikasi:
        </p>
        <div style="background-color: #f3f4f6; padding: 15px; text-align: center; border-radius: 8px; margin: 25px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #2563eb;">{{ $code }}</span>
        </div>
        <p style="color: #4b5563; font-size: 14px;">
            Jika Anda tidak merasa mendaftar di MyKlinik911, abaikan email ini.
        </p>
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">
        <p style="color: #9ca3af; font-size: 12px; text-align: center;">
            &copy; {{ date('Y') }} MyKlinik911. All rights reserved.
        </p>
    </div>
</body>
</html>
