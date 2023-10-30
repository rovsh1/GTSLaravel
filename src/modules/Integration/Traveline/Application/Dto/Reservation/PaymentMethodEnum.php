<?php

namespace Module\Integration\Traveline\Application\Dto\Reservation;

enum PaymentMethodEnum: string
{
    case Credit = 'CREDIT';
    case Cash = 'CASH';
    case Prepay = 'PREPAY';
}
