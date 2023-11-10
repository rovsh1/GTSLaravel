<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id')->from(100);
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('legal_id')->nullable();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->char('currency', 3);
            $table->tinyInteger('status');
            $table->string('source');
            $table->unsignedInteger('creator_id');
            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('currency')
                ->references('code_char')
                ->on('r_currencies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('legal_id')
                ->references('id')
                ->on('client_legals')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('invoice_id')
                ->references('id')
                ->on('client_invoices')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('creator_id')
                ->references('id')
                ->on('administrators')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        $this->upAdministratorOrders();
    }

    private function upAdministratorOrders(): void
    {
        Schema::create('administrator_orders', function (Blueprint $table) {
            $table->unsignedInteger('order_id')->primary();
            $table->unsignedInteger('administrator_id');

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('administrator_id')
                ->references('id')
                ->on('administrators')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
