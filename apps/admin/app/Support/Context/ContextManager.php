<?php

namespace App\Admin\Support\Context;

use Sdk\Shared\Contracts\Context\ContextInterface;
use Sdk\Shared\Enum\SourceEnum;
use Sdk\Shared\Support\ApplicationContext\AbstractContext;

class ContextManager extends AbstractContext implements ContextInterface
{
    public function __construct()
    {
        $this->generateRequestId();
        $this->setSource(SourceEnum::ADMIN);
    }
}
