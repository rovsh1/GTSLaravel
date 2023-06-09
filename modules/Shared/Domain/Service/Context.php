<?php

declare(strict_types=1);

namespace Module\Shared\Domain\Service;

use App\Core\Support\Facades\AppContext;

class Context
{
    public function get(): mixed
    {
        return AppContext::get();
    }
}
