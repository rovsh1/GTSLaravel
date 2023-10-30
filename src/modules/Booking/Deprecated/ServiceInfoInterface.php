<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated;

interface ServiceInfoInterface
{
    public function id(): int;

    public function name(): string;

    //цена + валюта
//    public function basePrice(): mixed;
}
