<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private static array $migrationAssoc = [
        'bed-type' => 3,
//        'room-name' => 2,
        'room-type' => 1,
        'hotel-type' => 5,
        'administrator-post' => 11,
        'payment-method' => 22,
//        'client-legal-requisite' => 7,
        'client-legal-industry' => 10,
        'hotel-usability-group' => 19
    ];

    private const CANCEL_REASON_ENUM_GROUP = 'cancel-reason';
    private const SUPPLIER_TYPE_ENUM_GROUP = 'supplier-type';

    private const DEFAULT_CANCEL_REASONS = [
        'Нет мест',
        'Двойное бронирование',
    ];

    private const DEFAULT_SUPPLIER_TYPES = [
        'Отель',
        'Транспортная компания',
        'Аэропорт',
        'Ресторан',
    ];

    public function up()
    {
        foreach (self::$migrationAssoc as $key => $id) {
            $flag = DB::table('r_enums')
                ->where('group', $key)
                ->exists();
            if ($flag) {
                continue;
            }

            $q = DB::connection('mysql_old')->table('r_enums')
                ->addSelect('r_enums.*')
                ->addSelect(
                    DB::raw(
                        '(SELECT name FROM r_enums_translation WHERE translatable_id=r_enums.id AND language="ru") as name'
                    )
                )
                ->where('group_id', $id);
            foreach ($q->cursor() as $r) {
                Db::table('r_enums')
                    ->insert([
                        'id' => $r->id,
                        'group' => $key
                    ]);

                Db::table('r_enums_translation')
                    ->insert([
                        'translatable_id' => $r->id,
                        'language' => 'ru',
                        'name' => $r->name
                    ]);
            }
        }
        $this->upCancelReasons();
        $this->upSupplierTypes();
    }

    private function upCancelReasons(): void
    {
        $alreadyExists = DB::table('r_enums')->where('group', self::CANCEL_REASON_ENUM_GROUP)->exists();
        if ($alreadyExists) {
            return;
        }

        foreach (self::DEFAULT_CANCEL_REASONS as $cancelReason) {
            $id = Db::table('r_enums')->insertGetId(['group' => self::CANCEL_REASON_ENUM_GROUP]);

            Db::table('r_enums_translation')->insert([
                'translatable_id' => $id,
                'language' => 'ru',
                'name' => $cancelReason
            ]);
        }
    }

    private function upSupplierTypes(): void
    {
        $alreadyExists = DB::table('r_enums')->where('group', self::SUPPLIER_TYPE_ENUM_GROUP)->exists();
        if ($alreadyExists) {
            return;
        }

        foreach (self::DEFAULT_SUPPLIER_TYPES as $index => $supplierType) {
            $id = Db::table('r_enums')->insertGetId(['group' => self::SUPPLIER_TYPE_ENUM_GROUP]);
            if ($supplierType === 'Отель') {
                Cache::set('hotel_supplier_type_id', $id);
            }

            Db::table('r_enums_translation')->insert([
                'translatable_id' => $id,
                'language' => 'ru',
                'name' => $supplierType
            ]);
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE r_enums_translation');
        DB::statement('TRUNCATE TABLE r_enums');
    }
};
