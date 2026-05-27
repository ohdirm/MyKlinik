<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_code',
        'patient_name',
        'nik',
        'phone',
        'birth_date',
        'gender',
        'address',
        'province',
        'district',
        'sub_district',
        'village',
        'doctor_id',
        'schedule_id',
        'exam_date',
        'queue_number',
        'status',
        'rejection_reason',
        'user_id',
        'complaint',
        'booking_source',
        'profile_type',
        'family_profile_id',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'exam_date' => 'date',
            'queue_number' => 'integer',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function familyProfile(): BelongsTo
    {
        return $this->belongsTo(FamilyProfile::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get status badge CSS class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'PENDING' => 'bg-yellow-100 text-yellow-800',
            'CONFIRMED' => 'bg-green-100 text-green-800',
            'EXAMINING' => 'bg-blue-100 text-blue-800',
            'REJECTED' => 'bg-red-100 text-red-800',
            'DONE' => 'bg-gray-100 text-gray-800',
            'CANCELLED' => 'bg-gray-100 text-gray-500',
            'EXPIRED' => 'bg-gray-200 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Menghitung estimasi kedatangan berdasarkan antrean.
     * Asumsi 15 menit per pasien sejak jadwal dimulai.
     */
    public function getEstimatedTimeAttribute(): ?string
    {
        if (! $this->schedule || ! $this->queue_number) {
            return null;
        }

        $startTime = Carbon::createFromTimeString($this->schedule->start_time);
        $minutesToAdd = ($this->queue_number - 1) * 15;

        return $startTime->addMinutes($minutesToAdd)->format('H:i');
    }

    /**
     * Generate WhatsApp confirmation link.
     */
    public function getWhatsappLinkAttribute(): string
    {
        $statusText = $this->status === 'CONFIRMED' ? '*DIKONFIRMASI*' : 'diterima';
        $sourceText = $this->booking_source === 'WALK_IN' ? '(Walk-in)' : '';

        $msg = urlencode(
            "*KONFIRMASI PENDAFTARAN - MyKlinik911*\n\n"
            ."Halo *{$this->patient_name}*, booking Anda telah {$statusText} {$sourceText}.\n\n"
            ."📌 *Detail Booking:*\n"
            ."- Kode: {$this->booking_code}\n"
            ."- No. Antrean: *{$this->queue_number}*\n\n"
            ."🩺 *Jadwal Periksa:*\n"
            .'- Dokter: '.($this->doctor->name ?? '-')."\n"
            .'- Tanggal: '.($this->exam_date ? $this->exam_date->format('d/m/Y') : '-')."\n"
            .'- Jam: '.($this->schedule->time_range ?? '-')."\n\n"
            ."💡 *Penting:*\n"
            ."- Datanglah *15 menit* sebelum jadwal untuk verifikasi.\n"
            ."- Tunjukkan pesan ini ke petugas pendaftaran.\n\n"
            .'Terima kasih.'
        );

        $phone = ltrim($this->phone, '0');
        // Ensure starting with 62
        if (! str_starts_with($phone, '62')) {
            $phone = '62'.$phone;
        }

        return "https://wa.me/{$phone}?text={$msg}";
    }
}
