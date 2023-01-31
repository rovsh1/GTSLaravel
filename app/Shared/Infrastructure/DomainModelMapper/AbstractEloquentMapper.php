<?php

namespace GTS\Shared\Infrastructure\DomainModelMapper;

use GTS\Shared\Infrastructure\Models\Model;

abstract class AbstractEloquentMapper implements MapperInterface
{
    abstract public static function from($value);

    public static function collection($collection): array
    {
        return $collection->map(fn(Model $model) => static::from($model))->all();
    }
}
