<?php

namespace App\Admin\Services\ChangeLogger;

enum EventTypeEnum
{
    case CREATED;
    case UPDATED;
    case DELETED;
}
