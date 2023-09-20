<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_invoice_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('booking_id');
            $table->unsignedTinyInteger('type');
            $table->string('number');
            $table->unsignedDecimal('payment_sum');
            $table->unsignedTinyInteger('payment_method');
            $table->string('document_name', 32)->nullable();
            $table->char('document', 32)->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('invoice_id')
                ->references('id')
                ->on('order_invoices')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('document')
                ->references('guid')
                ->on('files')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_invoice_payments');
    }
};
