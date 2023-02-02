<?php

namespace GTS\Shared\Domain\Factory;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Enumerable;
use Spatie\LaravelData\DataCollection;

interface FactoryInterface
{
    public static function createFrom(mixed ...$payloads);

    public static function createCollectionFrom(Enumerable|array|AbstractPaginator|Paginator|AbstractCursorPaginator|CursorPaginator|DataCollection $items): array;
}
