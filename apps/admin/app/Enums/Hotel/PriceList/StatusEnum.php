<?php

namespace App\Admin\Enums\Hotel\PriceList;

use App\Admin\Enums\TranslatableEnumTrait;

enum StatusEnum: int
{
    use TranslatableEnumTrait;

    public const LANG_PREFIX = 'enum.hotel.price-list.status';

    case NotProcessed = 0;
    case InProgress = 1;
    case Processed = 2;

    public function getLabel(): string
    {
        $prefix = self::LANG_PREFIX;
        return __("{$prefix}.{$this->name}");
    }
}
