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
            $employeeId = null;
            if ($r->employee_id !== null) {
                $oldEmployee = DB::connection('mysql_old')->table('hotel_employees')->where('id', $r->employee_id)->first();
                if ($oldEmployee !== null) {
                    $employeeId = DB::table('hotel_employees')->insertGetId([
                        'hotel_id' => $oldEmployee->hotel_id,
                        'fullname' => $oldEmployee->fullname,
                        'department' => $oldEmployee->department,
                        'post' => $oldEmployee->post,
                        'created_at' => $oldEmployee->created,
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('hotel_contacts')
                ->insert([
                    'hotel_id' => $r->hotel_id,
                    'employee_id' => $employeeId,
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
