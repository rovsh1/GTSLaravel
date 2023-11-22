<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $this->upServiceCancelConditions();
        $this->upCarsCancelConditions();
    }

    public function upServiceCancelConditions(): void
    {
        Schema::create('supplier_service_cancel_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('season_id');
            $table->unsignedInteger('service_id');
            $table->json('data');

            $table->foreign('season_id')
                ->references('id')
                ->on('supplier_seasons')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('service_id')
                ->references('id')
                ->on('supplier_services')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    private function upCarsCancelConditions(): void
    {
        Schema::create('supplier_car_cancel_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('season_id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('car_id');
            $table->json('data');

            $table->foreign('season_id')
                ->references('id')
                ->on('supplier_seasons')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('service_id')
                ->references('id')
                ->on('supplier_services')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('car_id')
                ->references('id')
                ->on('supplier_cars')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_service_cancel_conditions');
        Schema::dropIfExists('supplier_car_cancel_conditions');
    }
};
