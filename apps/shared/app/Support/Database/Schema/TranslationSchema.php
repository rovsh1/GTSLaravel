<?php

namespace App\Shared\Support\Database\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TranslationSchema
{
    public static function create(string $parentTable, \Closure $callback): void
    {
        Schema::create(self::tableName($parentTable), function (Blueprint $table) use ($parentTable, $callback) {
            self::addTranslatableId($parentTable, $table);
            $table->char('language', 2);

            $callback($table);

            $table->primary(['translatable_id', 'language']);

            $table->foreign('translatable_id', 'fk_' . $parentTable . '_translation_translatable_id')
                ->references('id')
                ->on($parentTable)
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public static function table(string $parentTable, \Closure $callback): void
    {
        Schema::table(self::tableName($parentTable), $callback);
    }

    public static function dropIfExists(string $parentTable): void
    {
        Schema::dropIfExists(self::tableName($parentTable));
    }

    private static function addTranslatableId(string $parentTable, Blueprint $table): void
    {
        $type = Schema::getColumnType($parentTable, 'id');
        $column = match ($type) {
            'integer' => $table->integer('translatable_id'),
            'smallint' => $table->smallInteger('translatable_id'),
            'bigint' => $table->bigInteger('translatable_id'),
        };
        $column->unsigned();
    }

    private static function tableName(string $parentTable): string
    {
        return "{$parentTable}_translation";
    }
}
