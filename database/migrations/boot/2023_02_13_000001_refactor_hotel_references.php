<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::rename('r_usabilities', 'r_hotel_usabilities');
        Schema::rename('r_usabilities_translation', 'r_hotel_usabilities_translation');
    }

    public function down() {}
};
