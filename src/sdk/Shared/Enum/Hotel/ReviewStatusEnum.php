<?php

declare(strict_types=1);

namespace Sdk\Shared\Enum\Hotel;

enum ReviewStatusEnum: int
{
    case NOT_PUBLIC = 1;
    case PUBLIC = 2;
}
