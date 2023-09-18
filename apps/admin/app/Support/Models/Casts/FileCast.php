<?php

namespace App\Admin\Support\Models\Casts;

use App\Shared\Support\Facades\FileStorage;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Module\Shared\Dto\FileDto;

class FileCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        } elseif (!is_string($value)) {
            return null;
        } else {
            return FileStorage::find($value);
        }
    }

    public function set($model, $key, $value, $attributes)
    {
        if (is_null($value)) {
            return null;
        } elseif (is_string($value)) {
            return $value;
        } elseif ($value instanceof FileDto) {
            return $value->guid;
        } else {
            return null;
        }
    }
}
