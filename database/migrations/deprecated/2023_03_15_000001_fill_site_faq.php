<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        $q = DB::connection('mysql_old')->table('site_faq')
            ->addSelect('site_faq.*')
            ->addSelect(
                DB::raw(
                    '(SELECT name FROM site_faq_translation WHERE translatable_id=site_faq.id AND language="ru") as name'
                )
            )
            ->addSelect(
                DB::raw(
                    '(SELECT `text` FROM site_faq_translation WHERE translatable_id=site_faq.id AND language="ru") as answer'
                )
            );
        foreach ($q->cursor() as $r) {
            Db::table('site_faq')->insert([
                'id' => $r->id,
                'type' => $r->type
            ]);

            Db::table('site_faq_translation')
                ->insert([
                    'translatable_id' => $r->id,
                    'language' => 'ru',
                    'question' => $r->name,
                    'answer' => $r->answer
                ]);
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE site_faq_translation');
        DB::statement('TRUNCATE TABLE site_faq');
    }
};
