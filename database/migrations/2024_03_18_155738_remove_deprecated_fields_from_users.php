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
        Schema::table('hotel_administrators', function (Blueprint $table) {
            $table->dropColumn(['patronymic', 'name', 'surname']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['patronymic', 'name', 'surname']);
        });

        Schema::table('administrators', function (Blueprint $table) {
            $table->dropColumn(['name', 'surname']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
