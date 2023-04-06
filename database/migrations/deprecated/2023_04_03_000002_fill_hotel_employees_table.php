<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_employees');

        foreach ($q->cursor() as $r) {
            DB::table('hotel_employees')
                ->insert([
                    'id' => $r->id,
                    'hotel_id' => $r->hotel_id,
                    'fullname' => $r->fullname,
                    'department' => $r->department,
                    'post' => $r->post,
                    'created_at' => $r->created,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
