<?php

namespace App\Admin\Components\Prototype;

enum PrototypeGroups: string
{
    case RESERVATION = 'reservation';
    case HOTEL = 'hotel';
    case REFERENCE = 'reference';
    case ADMINISTRATOR = 'administrator';
    case SITE = 'site';
    case CUSTOMER = 'customer';
}
