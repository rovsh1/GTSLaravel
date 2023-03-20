<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('r_showplaces')) {
            Schema::rename('r_showplace_types', 'r_landmark_types');
            Schema::rename('r_showplaces', 'r_landmarks');
        }

        Schema::table('r_countries', function (Blueprint $table) {
            $table->string('datetime_format', 20)->nullable()->default(null)->change();
            $table->string('date_format', 20)->nullable()->default(null)->change();
            $table->string('time_format', 20)->nullable()->default(null)->change();
        });
    }

    public function down() {}
};
