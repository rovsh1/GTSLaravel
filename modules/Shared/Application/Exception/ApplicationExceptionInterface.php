<?php

declare(strict_types=1);

namespace Module\Shared\Application\Exception;

interface ApplicationExceptionInterface extends \Throwable
{
    public function getHumanMessage(): string;
}
