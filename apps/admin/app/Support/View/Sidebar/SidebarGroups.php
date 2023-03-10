<?php

namespace App\Admin\Support\View\Sidebar;

enum SidebarGroups: string
{
    case RESERVATION = 'reservation';
    case HOTEL = 'hotel';
    case CLIENT = 'client';
    case SITE = 'site';
    case REPORTS = 'reports';
    case FINANCES = 'finances';
    case ADMINISTRATION = 'administration';
}
