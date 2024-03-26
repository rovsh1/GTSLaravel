<?php

use Illuminate\Database\Migrations\Migration;
use Sdk\Shared\Enum\CurrencyEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('price_lists');
        foreach ($q->cursor() as $r) {
            try {
                DB::table('client_currency_rates')
                    ->insert([
                        'id' => $r->id,
                        'client_id' => $r->client_id,
                        'currency' => CurrencyEnum::fromId($r->currency_id)->value,
                        'rate' => $r->rate,
                        'date_start' => $r->date_from,
                        'date_end' => $r->date_to,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            } catch (\Throwable) {
            }
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
