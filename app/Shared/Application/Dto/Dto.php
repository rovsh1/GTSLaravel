<?php

namespace GTS\Shared\Application\Dto;

use Spatie\LaravelData\Data;

use GTS\Shared\Infrastructure\Facade\DomainModelNormalizer;

class Dto extends Data
{
    protected static string $collectionClass = DtoCollection::class;

    protected static string $paginatedCollectionClass = PaginatedDtoCollection::class;

    protected static string $cursorPaginatedCollectionClass = CursorPaginatedDtoCollection::class;

    public static function normalizers(): array
    {
        return array_merge(parent::normalizers(), [
                DomainModelNormalizer::class
            ]
        );
    }
}
