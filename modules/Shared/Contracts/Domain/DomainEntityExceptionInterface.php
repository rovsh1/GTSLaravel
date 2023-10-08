<?php

namespace Module\Shared\Contracts\Domain;

use Module\Shared\Enum\ErrorCodeEnum;

interface DomainEntityExceptionInterface extends \Throwable
{
    public function domainCode(): ErrorCodeEnum;
}
