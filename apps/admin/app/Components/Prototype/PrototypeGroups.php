<?php

namespace App\Admin\Components\Prototype;

enum PrototypeGroups: string
{
    case ADMINISTRATOR = 'administrator';
    case REFERENCE = 'reference';
    case HOTEL = 'hotel';
    case RESERVATION = 'reservation';
    case SITE = 'site';
    case CUSTOMER = 'customer';
}
