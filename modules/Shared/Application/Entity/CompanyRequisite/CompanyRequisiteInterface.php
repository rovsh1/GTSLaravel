<?php

namespace Module\Shared\Application\Entity\CompanyRequisite;

interface CompanyRequisiteInterface
{
    public function key(): string;

    public function value(): mixed;

    public function default(): mixed;

    public function cast(): string;
}
