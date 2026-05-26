<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Notifications\BookingStatusNotification;
use Illuminate\Console\Command;

class ExpireOldBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Set old unprocessed bookings to expired status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->startOfDay();

        $expiredBookings = Booking::where('exam_date', '<', $today)
            ->whereIn('status', ['PENDING', 'CONFIRMED', 'EXAMINING'])
            ->get();

        $count = $expiredBookings->count();

        if ($count === 0) {
            $this->info('No old bookings to expire.');

            return;
        }

        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'EXPIRED']);

            // Notify user if exists
            if ($booking->user) {
                $booking->user->notify(new BookingStatusNotification($booking, 'expired'));
            }
        }

        $this->info("Successfully expired {$count} bookings.");
    }
}
