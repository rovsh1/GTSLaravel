<?php

namespace Services\CompanyRequisites\Entity;

interface CompanyRequisiteInterface
{
    public function key(): string;

    public function value(): mixed;

    public function default(): mixed;

    public function cast(): string;
}
