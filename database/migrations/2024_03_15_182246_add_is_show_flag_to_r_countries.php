<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('r_countries', function (Blueprint $table) {
            $table->boolean('is_show_in_lists')->after('priority')->default(false);
        });

        //Узбекистан
        DB::table('r_countries')->where('id', 1)->update(['is_show_in_lists' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r_countries', function (Blueprint $table) {
            $table->dropColumn('is_show_in_lists');
        });
    }
};
