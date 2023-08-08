<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject;

enum QuotaChangeTypeEnum: int
{
    case RESERVE_BY_BOOKING = 1;
    case BOOK_BY_BOOKING = 2;
    case CANCEL_RESERVE_BY_BOOKING = 3;
    case CANCEL_BOOK_BY_BOOKING = 4;
}
