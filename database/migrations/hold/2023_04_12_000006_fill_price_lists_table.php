<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('price_lists');
        foreach ($q->cursor() as $r) {
            DB::table('price_lists')
                ->insert([
                    'id' => $r->id,
                    'client_id' => $r->client_id,
                    'currency_id' => $r->currency_id,
                    'rate' => $r->rate,
                    'file_guid' => null,
                    'date_from' => $r->date_from,
                    'date_to' => $r->date_to,
                    'status' => $r->status,
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
        DB::table('price_lists')->truncate();
    }
};
