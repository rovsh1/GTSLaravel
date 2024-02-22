<?php

namespace App\Admin\Services\JournalLogger;

enum EventTypeEnum
{
    case CREATED;
    case UPDATED;
    case DELETED;
}
