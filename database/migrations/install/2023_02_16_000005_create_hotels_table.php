<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('city_id');
            $table->smallInteger('type_id')->unsigned();
            $table->char('currency', 3);
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->tinyInteger('visibility')->unsigned()->default(0);
            $table->float('rating')->unsigned()->nullable();
            $table->string('name', 100);
            $table->char('zipcode', 6)->nullable();
            $table->string('address');
            $table->string('address_en')->nullable();
            $table->double('address_lat', 11, 8)->nullable();
            $table->double('address_lon', 11, 8)->nullable();
            $table->integer('city_distance')->nullable();
            $table->json('markup_settings')->nullable();
            $table->json('time_settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('city_id')
                ->references('id')
                ->on('r_cities')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('type_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('currency')
                ->references('code_char')
                ->on('r_currencies')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        TranslationSchema::create('hotels', function (Blueprint $table) {
            $table->text('text')->nullable();
        });
    }

    public function down()
    {
        TranslationSchema::dropIfExists('hotels');
        Schema::dropIfExists('hotels');
    }
};
