<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('supplier_payments');
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('status');
            $table->unsignedInteger('supplier_id');
            $table->string('invoice_number');
            $table->char('payment_currency', 3);
            $table->unsignedDecimal('payment_sum',14);
            $table->unsignedSmallInteger('payment_method_id');
            $table->date('payment_date');
            $table->date('issue_date');
            $table->string('document_name', 32)->nullable();
            $table->char('document', 32)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('payment_method_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};
