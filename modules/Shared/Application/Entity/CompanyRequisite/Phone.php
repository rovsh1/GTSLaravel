<?php

namespace Module\Shared\Application\Entity\CompanyRequisite;

use Module\Shared\Application\Support\CompanyRequisite\AbstractCompanyRequisite;

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
