<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Module\Shared\Application\Service\ApplicationConstants;

return new class extends Migration {
    public function up()
    {
        Schema::create('s_constants', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->string('key');
            $table->string('value')->nullable();
        });

        $this->fill();
    }

    private function fill(): void
    {
        foreach (ApplicationConstants::getInstance() as $constant) {
            DB::table('s_constants')->insert([
                'key' => $constant->key(),
                'value' => $constant->default()
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('s_constants');
    }
};
