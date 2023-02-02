<?php

namespace GTS\Shared\Domain\Factory;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Enumerable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

abstract class AbstractEntityFactory extends Data implements FactoryInterface
{
    public static string $entity;

    protected static string $collectionClass = EntityCollection::class;

    protected static string $paginatedCollectionClass = PaginatedEntityCollection::class;

    protected static string $cursorPaginatedCollectionClass = CursorPaginatedEntityCollection::class;

    public static function createFrom(mixed ...$payloads)
    {
        $factory = static::from(...$payloads);
        return static::createEntity($factory);
    }

    public static function createCollectionFrom(Enumerable|array|AbstractPaginator|Paginator|AbstractCursorPaginator|CursorPaginator|DataCollection $items): array
    {
        $factory = static::collection($items);
        return array_map(fn($item) => static::createEntity($item), $factory->items());
    }

    protected static function createEntity(array|Arrayable $data)
    {
        $preparedArgs = $data;
        if ($data instanceof Arrayable) {
            $preparedArgs = $data->toArray();
        }
        return new (static::$entity)(...$preparedArgs);
    }
}
