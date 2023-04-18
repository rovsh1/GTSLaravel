<?php

namespace App\Admin\Enums\Hotel;

enum PriceListStatusEnum: int
{
    case NOT_PROCESSED = 0;
    case IN_PROGRESS = 1;
    case PROCESSED = 2;
}
