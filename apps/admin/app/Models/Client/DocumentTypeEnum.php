<?php

declare(strict_types=1);

namespace App\Admin\Models\Client;

enum DocumentTypeEnum: int
{
    case CONTRACT = 1;
    case OTHER = 10;
}
