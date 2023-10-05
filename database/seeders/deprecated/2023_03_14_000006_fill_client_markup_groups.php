<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::table('client_markup_groups')
            ->insert([
                'id' => 1,
                'name' => 'Базовая наценка',
                'type' => \Module\Pricing\Domain\Markup\ValueObject\MarkupValueTypeEnum::PERCENT,
                'value' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE client_markup_groups');
    }
};
