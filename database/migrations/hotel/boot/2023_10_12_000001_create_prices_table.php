<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $this->upDatesPrices();
    }

    private function upDatesPrices()
    {
        Schema::create('hotel_room_dates_prices', function (Blueprint $table) {
            //$table->integer('id')->unsigned()->autoIncrement();
            $table->integer('room_id')->unsigned();
            $table->date('date');
            $table->tinyInteger('guests_count')->unsigned();
            $table->tinyInteger('resident_type')->unsigned();
            $table->integer('aggregator_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_room_dates_prices');
    }
};
