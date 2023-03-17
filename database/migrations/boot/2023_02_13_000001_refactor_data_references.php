<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::rename('r_showplace_types', 'ref_landmark_types');
        Schema::rename('r_showplaces', 'ref_landmarks');
    }

    public function down() {}
};
