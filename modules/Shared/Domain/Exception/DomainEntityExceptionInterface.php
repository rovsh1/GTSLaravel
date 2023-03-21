<?php

namespace Module\Shared\Domain\Exception;

interface DomainEntityExceptionInterface extends \Throwable
{
    public function domainCode(): ErrorCodeEnum;
}
