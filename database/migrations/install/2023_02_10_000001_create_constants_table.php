<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('s_constants', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->string('key', 50);
            $table->string('name', 50);
            $table->string('value');
            $table->boolean('enabled')->default(false);
        });

        $this->fill();
    }

    private function fill()
    {
        $constant = new Module\Shared\Domain\Constant\WageRateMin();
        DB::table('s_constants')
            ->insert([
                'key' => $constant->key(),
                'name' => 'Базовая расчетная величина',
                'value' => '300000',
                'enabled' => true
            ]);
    }

    public function down()
    {
        Schema::dropIfExists('s_constants');
    }
};
