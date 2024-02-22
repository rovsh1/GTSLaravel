<?php

namespace Sdk\Shared\Enum\Hotel;

enum StatusEnum: int
{
    case DRAFT = 1;
    case PUBLISHED = 2;
    case BLOCKED = 3;
    case ARCHIVE = 4;
}
