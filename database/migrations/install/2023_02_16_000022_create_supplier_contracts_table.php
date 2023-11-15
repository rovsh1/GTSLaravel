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
        Schema::create('supplier_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('service_type');
            $table->date('date_start');
            $table->date('date_end');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        Schema::create('supplier_service_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('contract_id');

            $table->foreign('contract_id')
                ->references('id')
                ->on('supplier_contracts')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('service_id')
                ->references('id')
                ->on('supplier_services')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        Schema::create('supplier_contract_files', function (Blueprint $table) {
            $table->char('guid', 32)->primary();
            $table->unsignedInteger('contract_id');

            $table->foreign('contract_id')
                ->references('id')
                ->on('supplier_contracts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('guid')
                ->references('guid')
                ->on('files')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_service_contracts');
        Schema::dropIfExists('supplier_contracts');
    }
};
