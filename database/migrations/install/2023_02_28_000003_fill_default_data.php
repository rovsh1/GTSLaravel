<?php

use App\Admin\Models\Administrator\Administrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Administrator::create([
            'login' => 'developer',
            'presentation' => 'developer',
            'password' => '123456',
            'superuser' => 1
        ]);

        DB::unprepared(file_get_contents(__DIR__ . '/data_currencies.sql'));
        DB::unprepared(file_get_contents(__DIR__ . '/data_countries.sql'));
        DB::unprepared(file_get_contents(__DIR__ . '/data_cities.sql'));
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE r_cities_translation');
        DB::statement('TRUNCATE TABLE r_cities');
        DB::statement('TRUNCATE TABLE r_countries_translation');
        DB::statement('TRUNCATE TABLE r_countries');
        DB::statement('TRUNCATE TABLE r_currencies_translation');
        DB::statement('TRUNCATE TABLE r_currencies');
    }
};
