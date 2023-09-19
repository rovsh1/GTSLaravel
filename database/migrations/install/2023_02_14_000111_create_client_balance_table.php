<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_balance', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->unsignedDecimal('debit', 12, 2)->default(0);
            $table->unsignedDecimal('credit', 12, 2)->default(0);
//            $table->decimal('balance', 12, 2);
            $table->json('context')->nullable();
            $table->timestamp('created_at');

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_balance');
    }
};
