<?php

namespace Module\Shared\Contracts\Service;

interface ApplicationConstantsInterface extends \Iterator, \Countable
{
    public function basicCalculatedValue(): float;
}
