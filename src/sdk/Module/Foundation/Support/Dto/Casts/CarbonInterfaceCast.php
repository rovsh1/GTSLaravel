<?php

namespace Sdk\Module\Foundation\Support\Dto\Casts;

use Carbon\Carbon;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class CarbonInterfaceCast extends DateTimeInterfaceCast
{
    public function __construct(array|string|null $format = null, ?string $type = Carbon::class, ?string $setTimeZone = null, ?string $timeZone = null)
    {
        parent::__construct($format, $type, $setTimeZone, $timeZone);
    }
}
