<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        return;
        $this->upRequests();
        $this->upChanges();
        $this->upStatusHistory();
    }

    private function upRequests()
    {
        Schema::create('reservation_requests', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $this->addAdministratorColumn($table);
            $this->addReservationColumns($table);
            $table->tinyInteger('type')->unsigned();
            $table->tinyInteger('source')->unsigned();
            $table->tinyInteger('status')->unsigned();
            $table->dateTime('closed_at');
            $table->timestamps();
        });
    }

    private function upChanges()
    {
        Schema::create('reservation_changes', function (Blueprint $table) {
            $this->addReservationColumns($table);
            $table->string('attribute', 45);
            $table->string('value', 255)->nullable();
            $table->string('flag_added')->unsigned()->default(0);
            $table->string('flag_deleted')->unsigned()->default(0);
            $table->dateTime('created_at');
        });
    }

    private function upStatusHistory()
    {
        Schema::create('reservation_status_history', function (Blueprint $table) {
            $this->addReservationColumns($table);
            $this->addAdministratorColumn($table);
            $table->tinyInteger('source')->unsigned();
            $table->tinyInteger('status')->unsigned();
            $table->string('description', 255)->nullable();
            $table->dateTime('created_at');
        });
    }

    private function addReservationColumns($table)
    {
        $table->integer('reservation_id')->unsigned();
        $table->tinyInteger('reservation_type');
        $table->index(['reservation_id', 'reservation_type'], $table->getTable() . 'idx_reservation');
    }

    private function addAdministratorColumn($table)
    {
        $table->integer('administrator_id')->nullable()->unsigned();
        $table->foreign('administrator_id', $table->getTable() . '_fkey_administrator_id')
            ->references('id')
            ->on('administrators')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
    }

    public function down()
    {
        Schema::dropIfExists('reservation_requests');
        Schema::dropIfExists('reservation_changes');
        Schema::dropIfExists('reservation_status_history');
    }
};
