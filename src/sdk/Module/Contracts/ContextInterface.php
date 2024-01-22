<?php

namespace Sdk\Module\Contracts;

use Sdk\Shared\Contracts\Context\ContextInterface as CommonContextInterface;

interface ContextInterface extends CommonContextInterface
{
    public function module(): ModuleInterface;

    public function setPrevContext(array $data): void;
}
