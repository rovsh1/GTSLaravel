<?php

namespace Module\Shared\Contracts\Domain;

use Module\Shared\Enum\ErrorCodeEnum;

//TODO refactor
interface DomainEntityExceptionInterface extends \Throwable
{
    public function domainCode(): ErrorCodeEnum;
}
