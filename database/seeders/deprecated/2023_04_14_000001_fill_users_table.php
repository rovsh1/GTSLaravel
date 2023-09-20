<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('users');

        foreach ($q->cursor() as $user) {

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->truncate();
    }
};
