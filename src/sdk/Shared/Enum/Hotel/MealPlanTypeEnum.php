<?php

declare(strict_types=1);

namespace Sdk\Shared\Enum\Hotel;

enum MealPlanTypeEnum: int
{
    case UNKNOWN = 1;
    case ALL_INCLUSIVE = 2;
    case BUFFET_BREAKFAST = 3;
    case CONTINENTAL_BREAKFAST = 4;
    case ENGLISH_BREAKFAST = 5;
    case FULL_BOARD = 6;
    case HALF_BOARD = 7;
    case BREAKFAST = 8;
}
