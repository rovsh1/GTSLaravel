<?php

namespace Sdk\Module\Contracts;

use Sdk\Shared\Contracts\Context\ContextInterface as CommonContextInterface;
use Sdk\Shared\Contracts\Context\HttpContextInterface;

interface ContextInterface extends CommonContextInterface, HttpContextInterface
{
    public function module(): ModuleInterface;

    public function setPrevContext(array $data): void;
}
