<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $assoc = $this->refactorServiceTypes();

        $this->updateTable($assoc);
    }

    private function refactorServiceTypes(): array
    {
        DB::table('r_enums')
            ->where('group', 'hotel-service-type')
            ->delete();

        $q = DB::connection('mysql_old')->table('r_service_types')
            ->addSelect('r_service_types.id')
            ->addSelect(DB::raw('(SELECT name FROM r_service_types_translation WHERE translatable_id=r_service_types.id AND language="ru") as name'))
            ->get();
        foreach ($q as $r) {
            $id = DB::table('r_enums')
                ->insertGetId([
                    'group' => 'hotel-service-type',
                ]);
            $assoc[$r->id] = $id;

            DB::table('r_enums_translation')
                ->insert([
                    'translatable_id' => $id,
                    'language' => 'ru',
                    'name' => $r->name
                ]);
        }

        return $assoc;
    }

    private function updateTable(array $assoc)
    {
        $services = DB::connection('mysql_old')->table('r_services')
            ->addSelect('r_services.*')
            ->addSelect(DB::raw('(SELECT name FROM r_services_translation WHERE translatable_id=r_services.id AND language="ru") as name'))
            ->get();
        foreach ($services as $r) {
            DB::table('r_hotel_services')
                ->insert([
                    'id' => $r->id,
                    'type_id' => $assoc[$r->type_id] ?? null,
                ]);

            DB::table('r_hotel_services_translation')
                ->insert([
                    'translatable_id' => $r->id,
                    'language' => 'ru',
                    'name' => $r->name,
                ]);
        }
    }

    public function down() {}
};
