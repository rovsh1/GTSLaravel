<?php

namespace App\Admin\Enums;

enum ResourceGroup: string
{
    case ADMINISTRATOR = 'administrator';
    case REFERENCE = 'reference';
    case HOTEL = 'hotel';
    case RESERVATION = 'reservation';
}
