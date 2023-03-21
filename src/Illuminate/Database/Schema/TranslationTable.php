<?php

namespace Custom\Illuminate\Database\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

class TranslationTable
{
    private array $columns = [];

    public function __construct(
        private readonly string $table
    ) {}

    public function string($column, $length = null, array $parameters = []): self
    {
        $length = $length ?: Builder::$defaultStringLength;
        $preparedParameters = array_merge($parameters, compact('length'));
        return $this->addColumn('string', $column, $preparedParameters);
    }

    public function text($column, $length = null, array $parameters = []): self
    {
        $preparedParameters = array_merge($parameters, compact('length'));
        return $this->addColumn('text', $column, $preparedParameters);
    }

    private function addColumn($type, $name, array $parameters = []): self
    {
        $column = new \stdClass();
        $column->type = $type;
        $column->name = $name;
        $column->parameters = $parameters;
        $this->columns[] = $column;
        return $this;
    }

    public function create(): void
    {
        Schema::create($this->table . '_translation', function (Blueprint $table) {
            $this->addTranslatableId($table);
            $table->char('language', 2);

            foreach ($this->columns as $column) {
                $table->addColumn($column->type, $column->name, $column->parameters);
            }

            $table->primary(['translatable_id', 'language']);

            $table->foreign('translatable_id')
                ->references('id')
                ->on($this->table)
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function addTranslatableId(Blueprint $table): void
    {
        $type = Schema::getColumnType($this->table, 'id');
        $column = match ($type) {
            'integer' => $table->integer('translatable_id'),
            'smallint' => $table->smallInteger('translatable_id'),
            'bigint' => $table->bigInteger('translatable_id'),
        };
        $column->unsigned();
    }
}
