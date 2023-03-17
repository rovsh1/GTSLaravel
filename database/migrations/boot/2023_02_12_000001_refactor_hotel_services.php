<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
//        Schema::dropIfExists('hotel_ref_services_translation');
//        Schema::dropIfExists('hotel_ref_services');

        $assoc = $this->refactorServiceTypes();

        $this->updateServicesTable();

        $this->updateTable($assoc);

//        Schema::rename('r_usabilities', 'hotel_ref_usabilities');
//        Schema::rename('r_usabilities_translation', 'hotel_ref_usabilities_translation');

        //Schema::dropIfExists('r_service_types_translation');
        // Schema::dropIfExists('r_service_types');
    }

    private function refactorServiceTypes(): array
    {
        $groupId = 100;
        $assoc = [0 => null];

        $flag = DB::table('r_enum_groups')
            ->where('id', $groupId)
            ->exists();
        if (!$flag) {
            DB::table('r_enum_groups')
                ->insert([
                    'id' => $groupId,
                    'key' => 'hotel-service-type'
                ]);
        }

        DB::table('r_enums')
            ->where('group_id', $groupId)
            ->delete();

        $q = DB::table('r_service_types')
            ->addSelect('r_service_types.id')
            ->addSelect(DB::raw('(SELECT name FROM r_service_types_translation WHERE translatable_id=r_service_types.id AND language="ru") as name'))
            ->get();
        foreach ($q as $r) {
            $id = DB::table('r_enums')
                ->insertGetId([
                    'group_id' => $groupId,
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

    private function updateServicesTable()
    {
        Schema::table('r_services', function (Blueprint $table) {
            $table->integer('type_id')->unsigned()->nullable()->change();
        });
        Schema::table('r_services_translation', function (Blueprint $table) {
            $table->char('language', 2)->change();
        });
    }

    private function updateTable(array $assoc)
    {
        $services = DB::table('r_services')->get();
        foreach ($services as $r) {
            DB::table('r_services')
                ->where('id', $r->id)
                ->update([
                    'type_id' => $assoc[$r->type_id] ?? null,
                ]);
        }

        Schema::rename('r_services', 'hotel_ref_services');
        Schema::rename('r_services_translation', 'hotel_ref_services_translation');
    }

    private function createServicesTable()
    {
        Schema::create('hotel_ref_services', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('type_id')->unsigned()->default(0);

            $table->foreign('type_id', 'fkey_type_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
        Schema::create('hotel_ref_services_translation', function (Blueprint $table) {
            $table->integer('translatable_id')->unsigned()->autoIncrement();
            $table->char('language', 2);
            $table->string('name', 100)->nullable();

            $table->primary(['translatable_id', 'language']);

            $table->foreign('translatable_id', 'fkey_translatable_id')
                ->references('id')
                ->on('hotel_ref_services')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function fillNewTable(array $assoc)
    {
        $services = DB::table('r_services')
            ->addSelect('r_service_types.id')
            ->addSelect('r_service_types.type_id')
            ->addSelect(DB::raw('(SELECT name FROM r_service_translation WHERE translatable_id=r_services.id AND language="ru") as name'))
            ->get();
        foreach ($services as $r) {
            $id = DB::table('hotel_ref_services')
                ->insertGetId([
                    'type_id' => $assoc[$r->type_id] ?? null,
                ]);

            DB::table('hotel_ref_services_translation')
                ->insert([
                    'translatable_id' => $id,
                    'language' => 'ru',
                    'name' => $r->name
                ]);
        }
    }

    public function down() {}
};
