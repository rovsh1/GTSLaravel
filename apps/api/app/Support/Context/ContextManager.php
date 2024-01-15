<?php

namespace App\Api\Support\Context;

use Sdk\Shared\Contracts\Context\ContextInterface;
use Sdk\Shared\Contracts\Context\HttpContextInterface;
use Sdk\Shared\Enum\SourceEnum;
use Sdk\Shared\Support\ApplicationContext\AbstractContext;
use Sdk\Shared\Support\ApplicationContext\Concerns\HttpRequestContextTrait;

class ContextManager extends AbstractContext implements ContextInterface, HttpContextInterface
{
    use HttpRequestContextTrait;

    public function __construct()
    {
        $this->generateRequestId();
        $this->setSource(SourceEnum::API);
    }
}
