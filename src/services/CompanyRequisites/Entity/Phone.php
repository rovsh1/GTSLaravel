<?php

namespace Services\CompanyRequisites\Entity;

use Services\CompanyRequisites\AbstractCompanyRequisite;

final class Phone extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    protected string $cast = 'phone';

    public function name(): string
    {
        return 'Телефон';
    }

    public function default(): string
    {
        return '+998781209012';
    }
}
