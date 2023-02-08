<?php

namespace Custom\Dto;

use Custom\Dto\DataPipes\DefaultValuesDataPipe;
use GTS\Shared\Infrastructure\Facade\DomainModelNormalizer;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataPipeline;
use Spatie\LaravelData\DataPipes\AuthorizedDataPipe;
use Spatie\LaravelData\DataPipes\CastPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\MapPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\ValidatePropertiesDataPipe;

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

    public static function pipeline(): DataPipeline{
        return DataPipeline::create()
            ->into(static::class)
            ->through(AuthorizedDataPipe::class)
            ->through(MapPropertiesDataPipe::class)
            ->through(ValidatePropertiesDataPipe::class)
            ->through(DefaultValuesDataPipe::class)
            ->through(CastPropertiesDataPipe::class);
    }
}
