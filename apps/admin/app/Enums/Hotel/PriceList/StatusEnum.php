<?php

namespace App\Admin\Enums\Hotel\PriceList;

enum StatusEnum: int
{
    public const LANG_PREFIX = 'enum.hotel.price-list.status';

    case NotProcessed = 0;
    case InProgress = 1;
    case Processed = 2;
}
