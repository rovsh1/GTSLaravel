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
            DB::table('client_currency_rates')
                ->insert([
                    'id' => $r->id,
                    'client_id' => $r->client_id,
                    'currency_id' => $r->currency_id,
                    'rate' => $r->rate,
                    'date_start' => $r->date_from,
                    'date_end' => $r->date_to,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        $q = DB::connection('mysql_old')->table('price_lists_options')->where('entity', 'hotel');
        foreach ($q->cursor() as $r) {
            DB::table('client_currency_rate_hotels')
                ->insert([
                    'rate_id' => $r->price_list_id,
                    'hotel_id' => $r->entity_id,
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
