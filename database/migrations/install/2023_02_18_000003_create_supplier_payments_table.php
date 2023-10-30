<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('booking_id');
            $table->string('invoice_number');
            $table->unsignedDecimal('payment_sum');
            $table->unsignedSmallInteger('payment_method_id');
            $table->string('document_name', 32)->nullable();
            $table->char('document', 32)->nullable();
            $table->date('issue_date');
            $table->date('payment_date');
            $table->timestamp('created_at')->nullable();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('payment_method_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_payments');
    }
};
