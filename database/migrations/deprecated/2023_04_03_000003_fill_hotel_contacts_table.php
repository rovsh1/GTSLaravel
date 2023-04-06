<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_contacts');

        foreach ($q->cursor() as $r) {
            DB::table('hotel_contacts')
                ->insert([
                    'id' => $r->id,
                    'hotel_id' => $r->hotel_id,
                    'employee_id' => $r->employee_id,
                    'type' => $r->type,
                    'value' => $r->value,
                    'description' => $r->description,
                    'is_main' => $r->main,
                    'created_at' => now(),
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
