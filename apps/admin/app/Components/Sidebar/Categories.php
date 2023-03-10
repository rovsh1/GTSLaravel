<?php

namespace App\Admin\Components\Sidebar;

enum Categories: string
{
    case RESERVATION = 'reservation';
    case HOTEL = 'hotel';
    case CLIENT = 'client';
    case ADMINISTRATION = 'administration';
    case SITE = 'site';
    case FINANCES = 'finances';
    case REPORTS = 'reports';
}
