<?php

namespace Sdk\Module\Foundation\Support\Dto;

use Module\Shared\Support\Facade\DomainModelNormalizer;
use Sdk\Module\Foundation\Support\Dto\DataPipes\DefaultValuesDataPipe;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataPipeline;
use Spatie\LaravelData\DataPipes\AuthorizedDataPipe;
use Spatie\LaravelData\DataPipes\CastPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\FillRouteParameterPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\MapPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\ValidatePropertiesDataPipe;

/**
 * @deprecated
 */
class Dto extends Data
{
    protected static string $_collectionClass = DtoCollection::class;

    protected static string $_paginatedCollectionClass = PaginatedDtoCollection::class;

    protected static string $_cursorPaginatedCollectionClass = CursorPaginatedDtoCollection::class;

    public static function normalizers(): array
    {
        return array_merge(parent::normalizers(), [
                DomainModelNormalizer::class
            ]
        );
    }

    public static function pipeline(): DataPipeline
    {
        return DataPipeline::create()
            ->into(static::class)
            ->through(AuthorizedDataPipe::class)
            ->through(MapPropertiesDataPipe::class)
            ->through(FillRouteParameterPropertiesDataPipe::class)
            ->through(ValidatePropertiesDataPipe::class)
            ->through(DefaultValuesDataPipe::class)
            ->through(CastPropertiesDataPipe::class);
    }
}
