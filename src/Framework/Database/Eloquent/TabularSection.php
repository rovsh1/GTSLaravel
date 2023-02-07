<?php

namespace Custom\Framework\Database\Eloquent;

use Illuminate\Support\Facades\DB;

class TabularSection
{

    protected $model;

    protected $table;

    protected $attribute;

    protected $values;

    protected $changed = false;

    protected $changedValues;

    public function __construct($model, $table, $attribute)
    {
        $this->model = $model;

        $this->table = $table;

        $this->attribute = $attribute;

        $model::saved([$this, 'save']);
    }

    public function values($values = null)
    {
        if (null === $values) {
            if (null !== $this->values)
                return $this->values;

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

    public function save()
    {
        if (false === $this->changed)
            return;

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
