<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $this->createContracts();
        $this->createContractDocuments();
        $this->createSeasons();
        $this->createSeasonPrices();
    }

    private function createContracts(): void
    {
        Schema::create('hotel_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id');
            $table->unsignedTinyInteger('status');
            $table->date('date_start');
            $table->date('date_end');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function createContractDocuments(): void
    {
        Schema::create('hotel_contract_documents', function (Blueprint $table) {
            $table->char('guid', 32)->primary();
            $table->unsignedInteger('contract_id');

            $table->foreign('contract_id')
                ->references('id')
                ->on('hotel_contracts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('guid')
                ->references('guid')
                ->on('files')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function createSeasons(): void
    {
        Schema::create('hotel_seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contract_id');
            $table->string('name');
            $table->date('date_start');
            $table->date('date_end');
            $table->timestamps();

            $table->foreign('contract_id')
                ->references('id')
                ->on('hotel_contracts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function createSeasonPrices(): void
    {
        Schema::create('hotel_season_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('season_id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('room_id');
            $table->unsignedDecimal('price', 14);
            $table->comment('Таблица цен по сезонам по умолчанию, для заполнения календаря цен');

            $table->unique(['group_id', 'season_id', 'room_id']);

            $table->foreign('season_id')
                ->references('id')
                ->on('hotel_seasons')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('group_id')
                ->references('id')
                ->on('hotel_price_groups')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('room_id')
                ->references('id')
                ->on('hotel_rooms')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_season_prices');
        Schema::dropIfExists('hotel_seasons');
        Schema::dropIfExists('hotel_contracts');
    }
};
