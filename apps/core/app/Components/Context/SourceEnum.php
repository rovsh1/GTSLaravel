<?php

namespace App\Core\Components\Context;

enum SourceEnum: string
{
    case SITE = 'site';
    case ADMIN = 'admin';
    case HOTEL = 'hotel';
    case API = 'api';
    case CONSOLE = 'console';
}
