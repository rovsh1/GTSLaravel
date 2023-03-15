<?php

namespace Custom\Framework\Database\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TabularSection
{
    private Model $model;

    private string $table;

    private string $attribute;

    private ?array $values = null;

    private bool $changed = false;

    private ?array $changedValues = null;

    public function __construct(Model $model, string $table, string $attribute)
    {
        $this->model = $model;

        $this->table = $table;

        $this->attribute = $attribute;

        $model::saved([$this, 'save']);
    }

    public function values(array $values = null): array|static
    {
        if (null === $values) {
            if (null !== $this->values) {
                return $this->values;
            }

            return $this->values = DB::table($this->table)
                ->where($this->model->getForeignKey(), $this->model->id)
                ->pluck($this->attribute)
                ->toArray();
        } else {
            $this->changed = true;
            $this->changedValues = is_array($values) ? array_unique($values) : null;

            return $this;
        }
    }

    public function find($value): bool
    {
        return in_array($value, $this->values());
    }

    public function save(): void
    {
        if (false === $this->changed) {
            return;
        }

        $this->changed = false;

        DB::table($this->table)
            ->where($this->model->getForeignKey(), $this->model->id)
            ->delete();

        if ($this->changedValues) {
            $insert = [];
            foreach ($this->changedValues as $id) {
                $insert[] = [
                    $this->model->getForeignKey() => $this->model->id,
                    $this->attribute => $id
                ];
            }

            DB::table($this->table)
                ->insert($insert);

            $this->changedValues = null;
        }
    }
}
