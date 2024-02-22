<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_payment_landings', function (Blueprint $table) {
            $table->unsignedInteger('payment_id');
            $table->unsignedInteger('order_id');
            $table->unsignedDecimal('sum', 14);
            $table->timestamp('created_at');

            $table->unique(['payment_id', 'order_id']);

            $table->foreign('payment_id')
                ->references('id')
                ->on('client_payments')
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
        Schema::dropIfExists('client_payment_landings');
    }
};
