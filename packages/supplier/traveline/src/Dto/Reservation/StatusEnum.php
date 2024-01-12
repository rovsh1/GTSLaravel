<?php

namespace Pkg\Supplier\Traveline\Dto\Reservation;

enum StatusEnum: string
{
    case New = 'new';
    case Modified = 'modified';
    case Cancelled = 'cancelled';
}
