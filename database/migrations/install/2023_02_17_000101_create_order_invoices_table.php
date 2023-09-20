<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_invoices', function (Blueprint $table) {
            $table->increments('id')->from(100);
            $table->tinyInteger('status');
            $table->char('document', 32)->nullable();
            $table->timestamps();

            $table->foreign('document')
                ->references('guid')
                ->on('files')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        $this->ordersTable();
    }

    private function ordersTable(): void
    {
        Schema::create('order_invoice_orders', function (Blueprint $table) {
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
        Schema::dropIfExists('order_invoices');
    }
};
