<?php

namespace Module\Booking\EventSourcing\Domain\Service\EventDescriptor;

use Sdk\Shared\Dto\FileDto;

abstract class AbstractDescriptor
{
    protected function fileLink(?FileDto $fileDto): string
    {
        return $fileDto
            ? '<a href="' . $fileDto->url . ' " class="ui-attachment-link" target="_blank">скачать</a>'
            : '<i>&lt;Файл недоступен&gt;</i>';
    }

    protected function valuePresentation(mixed $value): string
    {
        if (is_null($value)) {
            return 'NULL';
        } elseif ($value instanceof \Stringable) {
            return $value->__toString();
        } elseif ($value instanceof \BackedEnum) {
            return $value->value;
        } elseif ($value instanceof \UnitEnum) {
            return $value->name;
        } else {
            return (string)$value;
        }
    }
}