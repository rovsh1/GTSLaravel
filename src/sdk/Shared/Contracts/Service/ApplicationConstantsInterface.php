<?php

namespace Sdk\Shared\Contracts\Service;

interface ApplicationConstantsInterface extends \Iterator, \Countable
{
    public function basicCalculatedValue(): float;
}
