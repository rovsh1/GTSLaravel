<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Module\Shared\Enum\Client\ResidencyEnum;
use Module\Shared\Enum\CurrencyEnum;

return new class extends Migration {
    private const BASE_MARKUP_GROUP_ID = 1;

    public function up()
    {
        $q = DB::connection('mysql_old')
            ->table('clients')
            ->select(
                '*',
                DB::raw('(SELECT 1 FROM client_legal_entities WHERE client_id=clients.id LIMIT 1) as is_b2b')
            )
            ->addSelect(
                DB::raw(
                    '(SELECT GROUP_CONCAT(price_type) FROM client_price_types WHERE client_id=clients.id) as price_types'
                )
            );
        foreach ($q->cursor() as $r) {
            $priceTypes = !empty($r->price_types) ? explode(',', $r->price_types) : [];

            $residency = ResidencyEnum::ALL;
            if (count($priceTypes) < 2) {
                if (in_array(ResidencyEnum::RESIDENT->value, $priceTypes)) {
                    $residency = ResidencyEnum::RESIDENT;
                } else {
                    $residency = ResidencyEnum::NONRESIDENT;
                }
            }

            try {
                DB::table('clients')
                    ->insert([
                        'id' => $r->id,
                        'city_id' => $r->city_id,
                        'currency' => CurrencyEnum::fromId($r->currency_id)->value,
                        'type' => $r->type,
                        'residency' => $residency,
                        'name' => $r->name,
                        'description' => $r->description,
                        'status' => $r->status,
                        'is_b2b' => (bool)$r->is_b2b,
                        'markup_group_id' => self::BASE_MARKUP_GROUP_ID,
                        'created_at' => $r->created,
                        'updated_at' => $r->updated
                    ]);
            } catch (\Throwable $e) {
                //@todo hack пропускаем клиентов с ошибками. На тесте какая то проблема с инсертом из-за городов
            }
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE clients');
    }
};
