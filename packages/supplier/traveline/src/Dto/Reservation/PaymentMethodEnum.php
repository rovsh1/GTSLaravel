<?php

namespace Pkg\Supplier\Traveline\Dto\Reservation;

enum PaymentMethodEnum: string
{
    case Credit = 'CREDIT';
    case Cash = 'CASH';
    case Prepay = 'PREPAY';
}
