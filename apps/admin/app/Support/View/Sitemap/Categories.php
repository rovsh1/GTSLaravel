<?php

namespace App\Admin\Support\View\Sitemap;

enum Categories: string
{
    case RESERVATION = 'reservation';
    case HOTEL = 'hotel';
    case FINANCE = 'finance';
    case CLIENT = 'client';
    case SITE = 'site';
    case REPORTS = 'reports';
    case ADMINISTRATION = 'administration';
}
