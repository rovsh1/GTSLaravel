<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_invoice_orders', function (Blueprint $table) {
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('order_id');

            $table->foreign('invoice_id')
                ->references('id')
                ->on('order_invoices')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_invoice_orders');
    }
};
