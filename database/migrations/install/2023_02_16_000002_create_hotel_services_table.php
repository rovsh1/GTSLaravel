<?php

use App\Shared\Support\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_hotel_services', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->smallInteger('type_id')->unsigned()->nullable();

            $table->foreign('type_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        (new TranslationTable('r_hotel_services'))
            ->string('name')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('r_hotel_services');
    }
};
