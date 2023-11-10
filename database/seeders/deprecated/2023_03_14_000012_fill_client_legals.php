<?php

use Illuminate\Database\Migrations\Migration;
use Module\Client\Moderation\Domain\ValueObject\BankRequisites;

return new class extends Migration {
    private static array $migrationAssoc = [
        'inn' => 14,
        'kpp' => 15,
        'current_account' => 16,
        'bank_name' => 17,
        'correspondent_account' => 18,
        'bik' => 19,
        'city_name' => 20,
        'okpo' => 21
    ];

    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('client_legal_entities');
        foreach ($q->cursor() as $r) {
            $requisites = DB::connection('mysql_old')
                ->table('client_legal_requisites')
                ->where('legal_id', $r->id)
                ->get()
                ->keyBy('enum_id')
                ->map
                ->value;

            $cityName = $requisites[self::$migrationAssoc['city_name']] ?? null;

            $bankRequisites = new BankRequisites(
                inn: $requisites[self::$migrationAssoc['inn']] ?? '',
                kpp: $requisites[self::$migrationAssoc['kpp']] ?? '',
                currentAccount: $requisites[self::$migrationAssoc['current_account']] ?? '',
                bankName: $requisites[self::$migrationAssoc['bank_name']] ?? '',
                correspondentAccount: $requisites[self::$migrationAssoc['correspondent_account']] ?? '',
                bik: $requisites[self::$migrationAssoc['bik']] ?? '',
                cityName: $cityName,
                okpo: $requisites[self::$migrationAssoc['okpo']] ?? '',
            );

            try {
                DB::table('client_legals')
                    ->insert([
                        'id' => $r->id,
                        'client_id' => $r->client_id,
                        'city_id' => $r->city_id,
                        'industry_id' => $r->industry_id,
                        'name' => $r->name,
                        'address' => $r->address,
                        'requisites' => json_encode($bankRequisites->toData()),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            } catch (\Throwable $e) {
                //@todo hack на проде есть юр. лица без клиентов
            }
        }
    }

    public function down(): void
    {
        \DB::table('client_legals')->truncate();
    }
};
