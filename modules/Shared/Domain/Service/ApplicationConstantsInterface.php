<?php

namespace Module\Shared\Domain\Service;

interface ApplicationConstantsInterface extends \Iterator, \Countable
{
    public function basicCalculatedValue(): float;
}
