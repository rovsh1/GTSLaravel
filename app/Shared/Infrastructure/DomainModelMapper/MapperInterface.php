<?php

namespace GTS\Shared\Infrastructure\DomainModelMapper;

interface MapperInterface
{
    public static function from($value);

    public static function collection($collection): array;
}
