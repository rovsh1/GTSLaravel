<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_invoices', function (Blueprint $table) {
            $table->increments('id')->from(100);
            $table->unsignedInteger('client_id');
            $table->tinyInteger('status');
            $table->char('document', 32)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->restrictOnDelete()
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
        Schema::dropIfExists('client_invoices');
    }
};
