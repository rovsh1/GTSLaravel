<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('client_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('status');
            $table->unsignedInteger('client_id');
            $table->string('invoice_number');
            $table->char('payment_currency', 3);
            $table->unsignedDecimal('payment_sum');
            $table->unsignedSmallInteger('payment_method_id');
            $table->date('payment_date');
            $table->date('issue_date');
            $table->string('document_name', 32)->nullable();
            $table->char('document', 32)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
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
        Schema::dropIfExists('client_payments');
    }
};
