<?php

namespace Sdk\Shared\Enum;

enum SourceEnum: string
{
    case SITE = 'site';
    case ADMIN = 'admin';
    case HOTEL = 'hotel';
    case API = 'api';
    case CONSOLE = 'console';
}
