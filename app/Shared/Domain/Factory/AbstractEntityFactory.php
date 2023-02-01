<?php

namespace GTS\Shared\Domain\Factory;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class AbstractEntityFactory
{
    public static function create(string $entityClass, array|Arrayable $data)
    {
        return app(DataFromArrayResolver::class)->execute(
            $entityClass,
            collect($data)
        );
    }

    /**
     * @param string $entityClass
     * @param Collection<int, Arrayable>|array<int, array> $collection
     * @return array
     */
    public static function createCollection(string $entityClass, $collection): array
    {
        if (is_array($collection) && \Arr::isList($collection)) {
            return array_map(fn(array $model) => static::create($entityClass, $model), $collection);
        }
        return $collection->map(function ($model) use ($entityClass) {
            return static::create($entityClass, $model);
        })->all();
    }
}
