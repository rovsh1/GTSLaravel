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
        $this->upEvents();
    }

    private function upRequests()
    {
        Schema::create('reservation_requests', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $this->addReservationColumns($table);
            $this->addAdministratorColumn($table);
            $table->tinyInteger('type')->unsigned();
            $table->tinyInteger('source')->unsigned();
            $table->tinyInteger('status')->unsigned();
            $table->char('file', 32);
            $table->dateTime('closed_at');
            $table->timestamps();
        });
    }

    private function upChanges()
    {
        Schema::create('reservation_editing_history', function (Blueprint $table) {
            $this->addReservationColumns($table);
            $this->addAdministratorColumn($table);
            $table->string('event', 45);
            $table->string('data', 225);
            $table->tinyInteger('revision')->unsigned();
            $table->dateTime('created_at');
        });
    }

    private function upEvents()
    {
        Schema::create('reservation_events', function (Blueprint $table) {
            $this->addReservationColumns($table);
            //$this->addAdministratorColumn($table);
            $table->string('event', 45);
            $table->string('data', 225);
            $table->dateTime('created_at');
        });
//        Schema::create('reservation_status_history', function (Blueprint $table) {
//            $this->addReservationColumns($table);
//            $this->addAdministratorColumn($table);
//            $table->tinyInteger('source')->unsigned();
//            $table->tinyInteger('status')->unsigned();
//            $table->string('description', 255)->nullable();
//            $table->dateTime('created_at');
//        });
    }

    private function upRequests1()
    {
        Schema::create('reservation_requests', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $this->addReservationColumns($table);
            $table->string('event', 45);
            $table->string('data', 225);
            $table->tinyInteger('revision')->unsigned();
            $table->dateTime('created_at');
        });
    }

    private function addReservationColumns($table)
    {
        $table->integer('reservation_id')->unsigned();
        $table->foreign('reservation_id', $table->getTable() . '_fkey_reservation_id')
            ->references('id')
            ->on('reservations')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
        //$table->tinyInteger('reservation_type');
        //$table->index(['reservation_id', 'reservation_type'], $table->getTable() . 'idx_reservation');
        //$table->index('reservation_id', 'reservation_type'], $table->getTable() . 'idx_reservation');
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
