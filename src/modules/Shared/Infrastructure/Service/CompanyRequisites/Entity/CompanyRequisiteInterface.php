<?php

namespace Module\Shared\Infrastructure\Service\CompanyRequisites\Entity;

interface CompanyRequisiteInterface
{
    public function key(): string;

    public function value(): mixed;

    public function default(): mixed;

    public function cast(): string;
}
