<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\DoctorStatus;
use Illuminate\Console\Command;

class ClinicResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clinic:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close clinic, reset doctor statuses, and cancel hanging bookings for the day.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        // Set all doctor statuses to UNAVAILABLE and queue to 0
        DoctorStatus::query()->update([
            'current_status' => 'UNAVAILABLE',
            'current_queue_number' => 0,
            'updated_at' => now(),
        ]);

        // Cancel all PENDING/CONFIRMED bookings for today
        Booking::whereDate('exam_date', $today)
            ->whereIn('status', ['PENDING', 'CONFIRMED'])
            ->update(['status' => 'CANCELLED']);

        // Log Activity
        ActivityLog::log('Reset Otomatis', 'Klinik ditutup otomatis oleh sistem. Status dokter direset dan booking gantung dibatalkan.');

        $this->info('Daily reset completed successfully.');
    }
}
