<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Booking;
use App\Models\DoctorStatus;

$examiningBookings = Booking::where('status', 'EXAMINING')->get();
foreach ($examiningBookings as $b) {
    DoctorStatus::updateOrCreate(
        ['doctor_id' => $b->doctor_id],
        [
            'current_status' => 'IN_EXAMINATION',
            'current_queue_number' => $b->queue_number,
            'updated_at' => now(),
        ]
    );
}
echo "Synced " . $examiningBookings->count() . " doctors.\n";
